<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view users',
            'create users',
            'update users',
            'delete users',
            'edit tasks',
            'delete tasks',
            'create tasks',
            'assign tasks',
            'unassign tasks',
            'view tasks',
            'view chat messages',
            'create chat messages',
            'update chat messages',
            'delete chat messages',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        $userRole = Role::create(['name' => 'user']);
    }
}
