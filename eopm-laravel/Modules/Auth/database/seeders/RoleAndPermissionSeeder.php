<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
        
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',
            
            'view products',
            'create products',
            'edit products',
            'delete products',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole = Role::create(['name' => 'Administrator']);
        $adminRole->givePermissionTo(Permission::all());

        $customerRole = Role::create(['name' => 'Customer']);
        $customerRole->givePermissionTo([
            'view orders',
            'create orders',
            'view payments',
            'create payments',
            'view products',
        ]);
        $this->command->info('Roles and Permissions seeded successfully!');
    }
}
