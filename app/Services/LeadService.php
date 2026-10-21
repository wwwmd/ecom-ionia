<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\User;

class LeadService
{
    public function create(array $data, ?User $creator = null): Lead
    {
        $data['created_by'] = $creator?->id;
        return Lead::create($data);
    }

    public function assign(Lead $lead, int $agentId): Lead
    {
        $lead->update([
            'assigned_agent_id' => $agentId,
            'assigned_at' => now(),
        ]);
        return $lead->fresh();
    }

    public function scheduleFollowup(Lead $lead, array $data, ?User $creator = null): LeadFollowup
    {
        $fu = LeadFollowup::create([
            'lead_id' => $lead->id,
            'customer_id' => $lead->customer_id,
            'agent_id' => $data['agent_id'] ?? $lead->assigned_agent_id,
            'type' => $data['type'] ?? 'call',
            'scheduled_at' => $data['scheduled_at'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'created_by' => $creator?->id,
        ]);

        $lead->update([
            'followup_date' => $fu->scheduled_at->toDateString(),
            'followup_time' => $fu->scheduled_at->format('H:i:s'),
        ]);

        return $fu;
    }

    public function convert(Lead $lead, int $orderId): Lead
    {
        $lead->update([
            'status' => 'converted',
            'converted_at' => now(),
            'converted_order_id' => $orderId,
        ]);
        return $lead->fresh();
    }
}
