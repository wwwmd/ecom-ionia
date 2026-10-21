<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Lead;

class AgentLeadController extends Controller
{
    public function index()
    {
        $leads = Lead::where('assigned_agent_id', auth()->id())
            ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(25);

        return view('agent.leads.index', compact('leads'));
    }

    public function show(Lead $lead)
    {
        abort_unless($lead->assigned_agent_id === auth()->id(), 403);
        $lead->load('followups', 'notes');

        return view('agent.leads.show', compact('lead'));
    }
}
