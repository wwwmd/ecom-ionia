<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id','name','email','phone','password','avatar','status',
        'gender','dob','last_login_at','last_login_ip','email_verified_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'dob' => 'date',
        'password' => 'hashed',
    ];

    /* ---------- Relationships ---------- */
    public function role() { return $this->belongsTo(Role::class); }
    public function customerProfile() { return $this->hasOne(CustomerProfile::class); }
    public function agentProfile() { return $this->hasOne(AgentProfile::class); }
    public function addresses() { return $this->hasMany(Address::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function wishlist() { return $this->hasMany(Wishlist::class); }
    public function cart() { return $this->hasOne(Cart::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function assignedLeads() { return $this->hasMany(Lead::class, 'assigned_agent_id'); }
    public function followups() { return $this->hasMany(LeadFollowup::class, 'agent_id'); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class); }

    /* ---------- Role helpers ---------- */
    public function isAdmin(): bool
    {
        return in_array($this->role->slug ?? '', ['super-admin','admin','manager']);
    }
    public function isSuperAdmin(): bool { return ($this->role->slug ?? '') === 'super-admin'; }
    public function isAgent(): bool { return ($this->role->slug ?? '') === 'agent'; }
    public function isCustomer(): bool { return ($this->role->slug ?? '') === 'customer'; }

    /* ---------- Permission helpers ---------- */
    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) return true;
        return $this->role && $this->role->permissions->contains('slug', $slug);
    }

    public function hasAnyPermission(array $slugs): bool
    {
        if ($this->isSuperAdmin()) return true;
        return $this->role && $this->role->permissions->whereIn('slug', $slugs)->isNotEmpty();
    }

    /* ---------- Scopes ---------- */
public function scopeWithRole($q, string $slug)
{
    return $q->whereHas('role', fn ($r) => $r->where('slug', $slug));
}

    /* ---------- Redirect based on role ---------- */
    public function getDashboardRouteAttribute(): string
    {
        return match ($this->role->slug ?? '') {
            'super-admin','admin','manager' => 'admin.dashboard',
            'agent' => 'agent.dashboard',
            'customer' => 'customer.dashboard',
            default => 'login',
        };
    }
}
