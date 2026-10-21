<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','slug','logo','banner','description','meta_title',
        'meta_description','is_featured','status'
    ];

    protected $casts = ['is_featured' => 'bool'];

    protected static function booted()
    {
        static::creating(function ($b) {
            if (empty($b->slug)) $b->slug = Str::slug($b->name);
        });
    }

    public function products() { return $this->hasMany(Product::class); }

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function getRouteKeyName() { return 'slug'; }
}
