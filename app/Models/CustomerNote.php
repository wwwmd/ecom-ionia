<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNote extends Model
{
    protected $fillable = ['customer_id', 'user_id', 'note', 'visibility'];

    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function author() { return $this->belongsTo(User::class, 'user_id'); }
}
