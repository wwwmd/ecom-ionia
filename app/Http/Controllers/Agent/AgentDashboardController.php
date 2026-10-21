<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\Order;

class AgentDashboardController extends Controller
{
    public function index()
    {
        $agentId = auth()->id();

        $stats = [
            'leads' => Lead::where('assigned_agent_id', $agentId)->count(),
            'followups' => LeadFollowup::where('agent_id', $agentId)
                ->whereDate('scheduled_at', today())
                ->where('status', 'pending')
                ->count(),
            'converted' => Lead::where('assigned_agent_id', $agentId)
                ->where('status', 'converted')
                ->count(),
            'commission' => Order::where('agent_id', $agentId)->sum('grand_total') * 0.05,
        ];

        $todayFollowups = LeadFollowup::with('lead')
            ->where('agent_id', $agentId)
            ->whereDate('scheduled_at', today())
            ->where('status', 'pending')
            ->orderBy('scheduled_at')
            ->get();

        $recentLeads = Lead::where('assigned_agent_id', $agentId)
            ->latest()
            ->take(5)
            ->get();

        return view('agent.dashboard', compact('stats', 'todayFollowups', 'recentLeads'));
    }
}
