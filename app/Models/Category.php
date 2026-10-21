<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id','name','slug','description','image','banner',
        'meta_title','meta_description','sort_order','is_featured','status'
    ];

    protected $casts = [
        'is_featured' => 'bool',
        'sort_order' => 'int',
    ];

    protected static function booted()
    {
        static::creating(function ($cat) {
            if (empty($cat->slug)) $cat->slug = Str::slug($cat->name);
        });
    }

    public function parent() { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children() { return $this->hasMany(Category::class, 'parent_id'); }
    public function products() { return $this->hasMany(Product::class); }

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }
    public function scopeParent($q) { return $q->whereNull('parent_id'); }

    public function getRouteKeyName() { return 'slug'; }
}
