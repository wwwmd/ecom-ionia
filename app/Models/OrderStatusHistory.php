<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = ['order_id', 'status', 'note', 'changed_by', 'changed_by_type'];

    public function order() { return $this->belongsTo(Order::class); }
    public function changedBy() { return $this->belongsTo(User::class, 'changed_by'); }
}
