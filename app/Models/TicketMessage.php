<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    protected $fillable = [
        'ticket_id','sender_type','sender_id','message','attachments','is_internal'
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'bool',
    ];

    public function ticket() { return $this->belongsTo(SupportTicket::class, 'ticket_id'); }
    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
}
