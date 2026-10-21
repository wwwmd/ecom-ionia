<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lead_code','customer_id','name','email','phone','source','product_interest',
        'product_id','message','status','priority','estimated_value','assigned_agent_id',
        'assigned_at','followup_date','followup_time','converted_at','converted_order_id',
        'lost_reason','remarks','created_by'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'followup_date' => 'date',
        'converted_at' => 'datetime',
        'estimated_value' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($lead) {
            if (empty($lead->lead_code)) {
                $nextId = static::max('id') + 1;
                $lead->lead_code = 'LD-'.strtoupper(now()->format('ymd')).'-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /* ---------- Relationships ---------- */
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function agent() { return $this->belongsTo(User::class, 'assigned_agent_id'); }
    public function product() { return $this->belongsTo(Product::class); }
    public function followups() { return $this->hasMany(LeadFollowup::class); }
    public function notes() { return $this->hasMany(LeadNote::class); }
    public function convertedOrder() { return $this->belongsTo(Order::class, 'converted_order_id'); }

    /* ---------- Scopes ---------- */
    public function scopeNew($q) { return $q->where('status', 'new'); }
    public function scopeConverted($q) { return $q->where('status', 'converted'); }
    public function scopeForAgent($q, $agentId) { return $q->where('assigned_agent_id', $agentId); }
    public function scopeTodayFollowups($q)
    {
        return $q->whereDate('followup_date', today());
    }
}
