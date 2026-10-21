<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'ticket_number','user_id','order_id','subject','category',
        'priority','status','assigned_to'
    ];

    protected static function booted()
    {
        static::creating(function ($t) {
            if (empty($t->ticket_number)) {
                $nextId = static::max('id') + 1;
                $t->ticket_number = 'TKT-'.strtoupper(now()->format('ymd')).'-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function messages() { return $this->hasMany(TicketMessage::class, 'ticket_id'); }

    public function scopeOpen($q) { return $q->whereIn('status', ['open', 'in_progress', 'waiting']); }
}
