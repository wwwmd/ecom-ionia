<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        // Super Admin bypasses all gates
        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) return true;
            return null;
        });

        // Register module-based permissions as gates
        $permissions = [
            'product.view','product.create','product.edit','product.delete','product.export',
            'category.view','category.manage',
            'brand.view','brand.manage',
            'order.view','order.create','order.edit','order.delete','order.export',
            'customer.view','customer.manage',
            'agent.view','agent.manage',
            'lead.view','lead.create','lead.edit','lead.delete','lead.assign',
            'followup.manage','crm.reports',
            'payment.view','payment.manage',
            'coupon.manage',
            'review.manage',
            'return.manage',
            'support.manage',
            'report.view',
            'settings.manage',
            'user.manage',
            'role.manage',
            'log.view',
        ];

        foreach ($permissions as $perm) {
            Gate::define($perm, function (User $user) use ($perm) {
                return $user->hasPermission($perm);
            });
        }
    }
}
