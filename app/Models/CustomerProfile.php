<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id','assigned_agent_id','total_orders','total_spent','mobile_verified_at'
    ];

    protected $casts = [
        'mobile_verified_at' => 'datetime',
        'total_spent' => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function assignedAgent() { return $this->belongsTo(User::class, 'assigned_agent_id'); }
}
