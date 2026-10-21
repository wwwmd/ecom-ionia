<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'variant_id', 'quantity', 'price'];
    protected $casts = ['price' => 'decimal:2', 'quantity' => 'int'];

    public function cart() { return $this->belongsTo(Cart::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class); }

    public function lineTotal(): float { return (float) ($this->price * $this->quantity); }
}
