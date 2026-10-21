<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id','variant_id','type','quantity','before_stock',
        'after_stock','reference_type','reference_id','note','created_by'
    ];

    protected $casts = ['quantity' => 'int'];

    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public static function recordIn(Product $product, int $qty, ?int $variantId = null): self
    {
        $before = $product->stock;
        $product->increment('stock', $qty);
        return static::create([
            'product_id' => $product->id,
            'variant_id' => $variantId,
            'type' => 'in',
            'quantity' => $qty,
            'before_stock' => $before,
            'after_stock' => $product->fresh()->stock,
            'created_by' => auth()->id(),
        ]);
    }

    public static function recordOut(Product $product, int $qty, ?int $variantId = null): self
    {
        $before = $product->stock;
        $product->decrement('stock', $qty);
        return static::create([
            'product_id' => $product->id,
            'variant_id' => $variantId,
            'type' => 'out',
            'quantity' => $qty,
            'before_stock' => $before,
            'after_stock' => $product->fresh()->stock,
            'created_by' => auth()->id(),
        ]);
    }
}
