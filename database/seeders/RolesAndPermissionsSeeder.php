<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
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
            'update users',
            'delete users',
            'delete tasks',
            'create tasks',
            'assign tasks',
            'view tasks',
            'view chat messages',
            'create chat messages',
            'update chat messages',
            'delete chat messages',
            'fulfill tasks',
            'update task status',
            'view deleted tasks',
            'restore tasks',
            'force delete tasks',
            'manage comments'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole = Role::create(['name' => UserRoles::ADMIN]);
        $adminRole->givePermissionTo($permissions);

        $userRole = Role::create(['name' => UserRoles::USER]);
    }
}
