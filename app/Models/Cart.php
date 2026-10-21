<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id', 'coupon_code'];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(CartItem::class); }

    public static function findOrCreateFor($user = null, $sessionId = null): self
    {
        $query = static::query();
        if ($user) $query->where('user_id', $user->id);
        else $query->where('session_id', $sessionId);

        return $query->first() ?? static::create([
            'user_id' => $user?->id,
            'session_id' => $sessionId,
        ]);
    }

    public function subtotal(): float
    {
        return (float) $this->items->sum(fn ($i) => $i->price * $i->quantity);
    }
}
