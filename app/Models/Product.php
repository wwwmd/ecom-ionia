<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id','brand_id','name','slug','sku','barcode','type',
        'short_description','description','specifications','price','compare_price',
        'cost_price','discount_type','discount_value','tax_rate','stock',
        'low_stock_alert','manage_stock','weight','length','width','height',
        'thumbnail','meta_title','meta_description','meta_keywords',
        'is_featured','is_trending','is_new_arrival','is_best_seller',
        'status','views','sold_count','avg_rating','review_count','created_by'
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_featured' => 'bool',
        'is_trending' => 'bool',
        'is_new_arrival' => 'bool',
        'is_best_seller' => 'bool',
        'manage_stock' => 'bool',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'avg_rating' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($p) {
            if (empty($p->slug)) $p->slug = Str::slug($p->name).'-'.Str::random(5);
            if (empty($p->sku)) $p->sku = 'SKU-'.strtoupper(Str::random(8));
        });
    }

    /* ---------- Relationships ---------- */
    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_attribute_values',
            'product_id',
            'attribute_value_id'
        )->withPivot('attribute_id');
    }

    /* ---------- Scopes ---------- */
    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }
    public function scopeTrending($q) { return $q->where('is_trending', true); }
    public function scopeNewArrival($q) { return $q->where('is_new_arrival', true); }
    public function scopeBestSeller($q) { return $q->where('is_best_seller', true); }
    public function scopeLowStock($q)
    {
        return $q->where('manage_stock', true)->whereColumn('stock', '<=', 'low_stock_alert');
    }

    /* ---------- Helpers ---------- */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->compare_price || $this->compare_price <= $this->price) return 0;
        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function isInStock(): bool
    {
        if (!$this->manage_stock) return true;
        return $this->stock > 0;
    }

    public function getRouteKeyName() { return 'slug'; }

    public function updateRatingStats(): void
    {
        $stats = $this->reviews()->where('status', 'approved');
        $this->update([
            'avg_rating' => round($stats->avg('rating') ?? 0, 2),
            'review_count' => $stats->count(),
        ]);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return self::resolveImageUrl($this->thumbnail);
    }

    public static function resolveImageUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }
        $clean = ltrim($path, '/');
        if (file_exists(public_path($clean))) {
            return asset($clean);
        }
        if (\Illuminate\Support\Str::startsWith($clean, 'storage/')) {
            return asset($clean);
        }
        if (\Illuminate\Support\Str::startsWith($clean, 'assets/')) {
            return asset($clean);
        }
        if (file_exists(public_path('assets/upload/' . basename($clean)))) {
            return asset('assets/upload/' . basename($clean));
        }
        return asset($clean);
    }
}
