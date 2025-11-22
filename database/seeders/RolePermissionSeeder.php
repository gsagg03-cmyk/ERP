<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (only if they don't exist)
        $permissions = [
            // User management
            'manage-owners',
            'manage-managers',
            'manage-salesmen',
            'view-all-users',
            
            // Product management
            'manage-products',
            'view-products',
            
            // Stock management
            'add-stock',
            'view-stock',
            
            // Sales management
            'create-sales',
            'view-own-sales',
            'view-all-sales',
            
            // Reports
            'view-reports',
            'view-all-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Sync permissions for existing roles
        
        // Super Admin
        $superAdmin = Role::where('name', 'superadmin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions([
                'manage-owners',
                'view-all-users',
                'manage-products',
                'view-products',
                'add-stock',
                'view-stock',
                'view-all-sales',
                'view-all-reports',
            ]);
        }

        // Owner
        $owner = Role::where('name', 'owner')->first();
        if ($owner) {
            $owner->syncPermissions([
                'manage-managers',
                'view-all-users',
                'manage-products',
                'view-products',
                'add-stock',
                'view-stock',
                'create-sales',
                'view-all-sales',
                'view-all-reports',
            ]);
        }

        // Manager
        $manager = Role::where('name', 'manager')->first();
        if ($manager) {
            $manager->syncPermissions([
                'manage-salesmen',
                'manage-products',
                'view-products',
                'add-stock',
                'view-stock',
                'create-sales',
                'view-all-sales',
                'view-reports',
            ]);
        }

        // Salesman
        $salesman = Role::where('name', 'salesman')->first();
        if ($salesman) {
            $salesman->syncPermissions([
                'view-products',
                'create-sales',
                'view-own-sales',
            ]);
        }
    }
}
