<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadStoreRequest;
use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(private LeadService $service) {}

    public function index(Request $request)
    {
        $leads = Lead::with(['agent', 'customer'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->priority, fn ($q, $s) => $q->where('priority', $s))
            ->when($request->agent, fn ($q, $s) => $q->where('assigned_agent_id', $s))
            ->when($request->source, fn ($q, $s) => $q->where('source', $s))
            ->latest()
            ->paginate(25);

        $agents = User::withRole('agent')->get();

        $stats = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
        ];

        return view('admin.crm.leads.index', compact('leads', 'agents', 'stats'));
    }

    public function create()
    {
        return view('admin.crm.leads.create', [
            'agents' => User::withRole('agent')->get(),
        ]);
    }

    public function store(LeadStoreRequest $request)
    {
        $lead = $this->service->create($request->validated(), auth()->user());

        ActivityLog::record('crm', 'lead_create', "Lead created: {$lead->name}");

        return redirect()->route('admin.leads')
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['agent', 'followups', 'notes', 'customer']);

        return view('admin.crm.leads.show', [
            'lead' => $lead,
            'agents' => User::withRole('agent')->get(),
        ]);
    }

    public function assign(Request $request, Lead $lead)
    {
        $data = $request->validate(['agent_id' => 'required|exists:users,id']);
        $this->service->assign($lead, $data['agent_id']);

        ActivityLog::record('crm', 'lead_assign', "Lead {$lead->lead_code} assigned");

        return response()->json(['success' => true]);
    }
}
