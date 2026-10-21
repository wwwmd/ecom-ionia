<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id','sku','name','attributes_json','price','compare_price',
        'cost_price','stock','weight','image','status'
    ];

    protected $casts = [
        'attributes_json' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function getImageUrlAttribute(): ?string { return Product::resolveImageUrl($this->image); }
}
