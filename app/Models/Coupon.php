<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code','title','description','type','value','min_order_amount','max_discount',
        'applies_to','applies_ids','usage_limit','usage_limit_per_customer','used_count',
        'start_date','end_date','status'
    ];

    protected $casts = [
        'applies_ids' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    public function usages() { return $this->hasMany(CouponUsage::class); }

    public function isValid(float $subtotal = 0): bool
    {
        if ($this->status !== 'active') return false;
        if ($this->start_date && now()->lt($this->start_date)) return false;
        if ($this->end_date && now()->gt($this->end_date)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($subtotal && $subtotal < $this->min_order_amount) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        $discount = match ($this->type) {
            'percent' => $subtotal * ($this->value / 100),
            'fixed' => (float) $this->value,
            default => 0,
        };
        if ($this->max_discount) $discount = min($discount, (float) $this->max_discount);
        return round($discount, 2);
    }
}
