<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super-admin')->firstOrFail();
        $agentRole      = Role::where('slug', 'agent')->firstOrFail();
        $customerRole   = Role::where('slug', 'customer')->firstOrFail();

        // ---------- SUPER ADMIN ----------
        User::updateOrCreate(
            ['email' => 'admin@store.com'],
            [
                'role_id' => $superAdminRole->id,
                'name' => 'Super Admin',
                'phone' => '9999999999',
                'password' => Hash::make('Admin@123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // ---------- SAMPLE AGENT ----------
        $agent = User::updateOrCreate(
            ['email' => 'agent@store.com'],
            [
                'role_id' => $agentRole->id,
                'name' => 'Ravi Agent',
                'phone' => '9876543210',
                'password' => Hash::make('Agent@123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $agent->agentProfile()->updateOrCreate(
            ['user_id' => $agent->id],
            [
                'employee_code' => 'AGT-0001',
                'designation' => 'Sales Executive',
                'commission_rate' => 5.00,
                'target_amount' => 500000,
                'joined_at' => now()->subMonths(6),
            ]
        );

        // ---------- SAMPLE CUSTOMER ----------
        $customer = User::updateOrCreate(
            ['email' => 'customer@store.com'],
            [
                'role_id' => $customerRole->id,
                'name' => 'Test Customer',
                'phone' => '9876500000',
                'password' => Hash::make('Customer@123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $customer->customerProfile()->updateOrCreate(
            ['user_id' => $customer->id],
            [
                'total_orders' => 0,
                'total_spent' => 0,
            ]
        );

        $this->command->info('✅ Admin, Agent and Customer created.');
        $this->command->info('   Admin:    admin@store.com    / Admin@123');
        $this->command->info('   Agent:    agent@store.com    / Agent@123');
        $this->command->info('   Customer: customer@store.com / Customer@123');
    }
}
