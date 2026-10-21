<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'name','email','phone','subject','message','source',
        'status','assigned_agent_id'
    ];

    public function agent() { return $this->belongsTo(User::class, 'assigned_agent_id'); }

    public function scopeNew($q) { return $q->where('status', 'new'); }
}
