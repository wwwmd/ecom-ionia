<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'name', 'status', 'token'];

    protected static function booted()
    {
        static::creating(function ($s) {
            if (empty($s->token)) $s->token = Str::random(64);
        });
    }

    public function scopeSubscribed($q) { return $q->where('status', 'subscribed'); }
}
