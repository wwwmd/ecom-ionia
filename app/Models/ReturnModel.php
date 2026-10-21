<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'return_number','order_id','user_id','type','reason',
        'status','amount','admin_note'
    ];

    protected $casts = ['amount' => 'decimal:2'];

    protected static function booted()
    {
        static::creating(function ($r) {
            if (empty($r->return_number)) {
                $nextId = static::max('id') + 1;
                $r->return_number = 'RET-'.strtoupper(now()->format('ymd')).'-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(ReturnItem::class, 'return_id'); }
    public function refunds() { return $this->hasMany(Refund::class, 'return_id'); }
}
