<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentProfile extends Model
{
    protected $fillable = [
        'user_id','employee_code','designation','commission_rate','target_amount',
        'joined_at','address','city','state','country','pincode'
    ];

    protected $casts = [
        'joined_at' => 'date',
        'commission_rate' => 'decimal:2',
        'target_amount' => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
