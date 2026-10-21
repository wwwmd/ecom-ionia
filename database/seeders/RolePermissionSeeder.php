<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- ROLES ----------
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full access to all modules', 'is_system' => true],
            ['name' => 'Admin',       'slug' => 'admin',       'description' => 'Administrative access',       'is_system' => true],
            ['name' => 'Manager',     'slug' => 'manager',     'description' => 'Limited management access',   'is_system' => false],
            ['name' => 'Agent',       'slug' => 'agent',       'description' => 'CRM agent',                   'is_system' => true],
            ['name' => 'Customer',    'slug' => 'customer',    'description' => 'Storefront customer',         'is_system' => true],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['slug' => $r['slug']], $r);
        }

        // ---------- PERMISSIONS ----------
        $permissions = [
            'products'   => ['view', 'create', 'edit', 'delete', 'export'],
            'categories' => ['view', 'manage'],
            'brands'     => ['view', 'manage'],
            'orders'     => ['view', 'create', 'edit', 'delete', 'export'],
            'customers'  => ['view', 'manage'],
            'agents'     => ['view', 'manage'],
            'leads'      => ['view', 'create', 'edit', 'delete', 'assign'],
            'followups'  => ['manage'],
            'crm'        => ['reports'],
            'payments'   => ['view', 'manage'],
            'coupons'    => ['manage'],
            'reviews'    => ['manage'],
            'returns'    => ['manage'],
            'support'    => ['manage'],
            'reports'    => ['view'],
            'settings'   => ['manage'],
            'users'      => ['manage'],
            'roles'      => ['manage'],
            'logs'       => ['view'],
        ];

        // Slug pattern mapping (singular form)
        $slugMap = [
            'products' => 'product', 'categories' => 'category', 'brands' => 'brand',
            'orders' => 'order', 'customers' => 'customer', 'agents' => 'agent',
            'leads' => 'lead', 'followups' => 'followup', 'crm' => 'crm',
            'payments' => 'payment', 'coupons' => 'coupon', 'reviews' => 'review',
            'returns' => 'return', 'support' => 'support', 'reports' => 'report',
            'settings' => 'settings', 'users' => 'user', 'roles' => 'role', 'logs' => 'log',
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                $singular = $slugMap[$module] ?? $module;
                $slug = $singular.'.'.$action;

                Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'module' => $module,
                        'name' => ucfirst($action).' '.ucfirst($module),
                        'slug' => $slug,
                    ]
                );
            }
        }

        // ---------- ASSIGN PERMISSIONS ----------

        // Super Admin: ALL permissions
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $superAdmin->permissions()->sync(Permission::pluck('id'));

        // Admin: everything except role.manage
        $admin = Role::where('slug', 'admin')->first();
        $admin->permissions()->sync(
            Permission::where('slug', '!=', 'role.manage')->pluck('id')
        );

        // Manager: subset (no role.manage, no user.manage, no settings.manage)
        $manager = Role::where('slug', 'manager')->first();
        $manager->permissions()->sync(
            Permission::whereNotIn('slug', ['role.manage', 'user.manage', 'settings.manage'])->pluck('id')
        );

        // Agent: limited
        $agent = Role::where('slug', 'agent')->first();
        $agent->permissions()->sync(
            Permission::whereIn('slug', [
                'lead.view','lead.create','lead.edit','lead.assign',
                'followup.manage','customer.view','order.view','crm.reports',
            ])->pluck('id')
        );

        // Customer: NO admin permissions (only storefront)
    }
}
