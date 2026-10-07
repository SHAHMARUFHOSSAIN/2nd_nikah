<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define standard permissions
        $permissions = [
            'access_admin_panel',
            'manage_users',
            'manage_roles',
            'manage_member_profiles',
            'manage_memberships',
            'manage_payments',
            'manage_conversations',
            'manage_reports',
            'manage_cms',
            'manage_settings',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        // 2. Define standard roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'access_admin_panel',
            'manage_users',
            'manage_member_profiles',
            'manage_memberships',
            'manage_payments',
            'manage_conversations',
            'manage_reports',
            'manage_cms',
            'manage_settings',
        ]);

        $moderator = Role::firstOrCreate(['name' => 'Moderator', 'guard_name' => 'web']);
        $moderator->syncPermissions([
            'access_admin_panel',
            'manage_member_profiles',
            'manage_reports',
            'manage_conversations',
        ]);

        $support = Role::firstOrCreate(['name' => 'Support Manager', 'guard_name' => 'web']);
        $support->syncPermissions([
            'access_admin_panel',
            'manage_reports',
            'manage_conversations',
            'manage_member_profiles',
        ]);

        $member = Role::firstOrCreate(['name' => 'Member', 'guard_name' => 'web']);
        // Members don't have admin permissions

        // 3. Assign Super Admin role to all existing admins
        $adminUsers = User::where('is_admin', true)->get();
        foreach ($adminUsers as $adminUser) {
            if (! $adminUser->hasRole('Super Admin')) {
                $adminUser->assignRole($superAdmin);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe down: do not delete user records
    }
};
