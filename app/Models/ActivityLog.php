<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id','user_type','module','action','description',
        'subject_type','subject_id','properties','ip_address','user_agent'
    ];

    protected $casts = ['properties' => 'array'];

    public function user() { return $this->belongsTo(User::class); }

    public static function record(
        string $module,
        string $action,
        ?string $description = null,
        array $props = []
    ): void {
        $user = auth()->user();
        static::create([
            'user_id' => $user?->id,
            'user_type' => $user
                ? ($user->isCustomer() ? 'customer' : ($user->isAgent() ? 'agent' : 'admin'))
                : 'system',
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'properties' => $props,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 250),
        ]);
    }

    public function scopeModule($q, string $module) { return $q->where('module', $module); }
    public function scopeByUser($q, $userId) { return $q->where('user_id', $userId); }
}
