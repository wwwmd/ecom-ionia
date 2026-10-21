<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = ['attribute_id', 'value', 'slug', 'color_code', 'sort_order'];

    public function attribute() { return $this->belongsTo(Attribute::class); }
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_attribute_values',
            'attribute_value_id',
            'product_id'
        );
    }
}
