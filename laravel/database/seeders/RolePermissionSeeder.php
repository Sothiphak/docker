<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            'users.manage',
            'products.create', 'products.update', 'products.delete',
            'categories.create', 'categories.update', 'categories.delete'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // 2. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        // 3. Assign Permissions to Roles
        // Admin: Gets all permissions
        $adminRole->permissions()->sync(Permission::all());

        // Manager: Can manage products and categories, but NOT users
        $managerPermissions = Permission::where('name', '!=', 'users.manage')->get();
        $managerRole->permissions()->sync($managerPermissions);

        // Staff: Gets no special permissions (permissions array is empty for now)
        // (Staff usually just view tasks, which we handle via logic, not DB permissions)

        // 4. Create Users and Assign Roles

        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password')]
        );
        $admin->roles()->sync([$adminRole->id]);

        // Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            ['name' => 'Manager User', 'password' => Hash::make('password')]
        );
        $manager->roles()->sync([$managerRole->id]);

        // Staff User 1
        $staff1 = User::firstOrCreate(
            ['email' => 'staff1@example.com'],
            ['name' => 'Staff One', 'password' => Hash::make('password')]
        );
        $staff1->roles()->sync([$staffRole->id]);

        // Staff User 2
        $staff2 = User::firstOrCreate(
            ['email' => 'staff2@example.com'],
            ['name' => 'Staff Two', 'password' => Hash::make('password')]
        );
        $staff2->roles()->sync([$staffRole->id]);
    }
}
