<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id','user_id','gateway','gateway_txn_id','amount','currency',
        'status','method','meta','paid_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
}
