<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Services\LeadService;
use Illuminate\Http\Request;

class FollowupController extends Controller
{
    public function __construct(private LeadService $service) {}

    public function index()
    {
        $followups = LeadFollowup::with(['lead', 'agent'])
            ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
            ->when(request('date'), fn ($q, $d) => $q->whereDate('scheduled_at', $d))
            ->orderBy('scheduled_at')
            ->paginate(25);

        return view('admin.crm.followups.index', compact('followups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'agent_id' => 'nullable|exists:users,id',
            'type' => 'required|in:call,whatsapp,email,meeting,other',
            'scheduled_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($data['lead_id']);
        $this->service->scheduleFollowup($lead, $data, auth()->user());

        return response()->json(['success' => true]);
    }

    public function complete(LeadFollowup $followup)
    {
        $followup->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
