<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title','subtitle','image','mobile_image','link','button_text',
        'position','sort_order','start_date','end_date','status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sort_order' => 'int',
    ];

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeHero($q) { return $q->where('position', 'hero'); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order'); }
}
