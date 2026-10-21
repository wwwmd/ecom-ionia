<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id','user_id','order_id','rating','title','review',
        'images','is_featured','status','admin_reply'
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'bool',
        'rating' => 'int',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function customer() { return $this->belongsTo(User::class, 'user_id'); }
    public function order() { return $this->belongsTo(Order::class); }

    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }

    /* ---------- Auto-update product rating ---------- */
    protected static function booted()
    {
        static::saved(function ($review) {
            $review->product?->updateRatingStats();
        });
        static::deleted(function ($review) {
            $review->product?->updateRatingStats();
        });
    }
}
