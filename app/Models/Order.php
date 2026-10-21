<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number','user_id','agent_id','coupon_id','coupon_code','status',
        'payment_status','payment_method','shipping_method','currency',
        'subtotal','discount','tax','shipping_cost','grand_total','paid_amount',
        'customer_name','customer_email','customer_phone','shipping_address',
        'billing_address','customer_note','admin_note','tracking_number',
        'tracking_url','shipped_at','delivered_at','cancelled_at','cancellation_reason'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /* ---------- Relationships ---------- */
    public function user() { return $this->belongsTo(User::class); }
    public function agent() { return $this->belongsTo(User::class, 'agent_id'); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function statusHistory() { return $this->hasMany(OrderStatusHistory::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
    public function coupon() { return $this->belongsTo(Coupon::class); }

    /* ---------- Scopes ---------- */
    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeDelivered($q) { return $q->where('status', 'delivered'); }
    public function scopeToday($q) { return $q->whereDate('created_at', today()); }

    /* ---------- Helpers ---------- */
    public static function generateNumber(): string
    {
        $lastId = static::max('id') + 1;
        return 'ORD-'.strtoupper(now()->format('ymd')).'-'.str_pad((string) $lastId, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'confirmed', 'processing']);
    }
}
