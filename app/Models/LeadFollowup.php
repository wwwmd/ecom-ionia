<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    protected $fillable = [
        'lead_id','customer_id','agent_id','type','scheduled_at',
        'notes','outcome','status','completed_at','created_by'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function lead() { return $this->belongsTo(Lead::class); }
    public function agent() { return $this->belongsTo(User::class, 'agent_id'); }
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }

    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeToday($q) { return $q->whereDate('scheduled_at', today()); }
    public function scopeForAgent($q, $agentId) { return $q->where('agent_id', $agentId); }
}
