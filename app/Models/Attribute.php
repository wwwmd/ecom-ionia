<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'is_variant', 'status'];
    protected $casts = ['is_variant' => 'bool'];

    public function values() { return $this->hasMany(AttributeValue::class); }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute_values');
    }
}
