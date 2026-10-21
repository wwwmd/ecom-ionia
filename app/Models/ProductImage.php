<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'alt', 'is_primary', 'sort_order'];
    protected $casts = ['is_primary' => 'bool'];

    public function product() { return $this->belongsTo(Product::class); }
    public function getUrlAttribute(): ?string { return Product::resolveImageUrl($this->path); }
}
