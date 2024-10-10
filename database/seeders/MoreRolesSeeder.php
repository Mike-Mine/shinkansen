<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class MoreRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chatPermissions = collect([
            'view chat messages',
            'create chat messages',
            'update chat messages',
            'delete chat messages',
        ]);

        $userPermissions = collect([
            'view users',
            'create users',
            'update users',
            'delete users',
        ]);

        $taskPermissions = collect([
            'edit tasks',
            'delete tasks',
            'create tasks',
            'assign tasks',
            'view tasks',
        ]);

        // reporter, assignee, manager

        $managerRole = Role::create(['name' => UserRoles::MANAGER]);
        $managerRole->givePermissionTo($chatPermissions
            ->merge($taskPermissions)
            ->merge($userPermissions->filter(function ($permission) {
                return in_array($permission, ['view users', 'update users']);
            }))
        );

        $reporterRole = Role::create(['name' => UserRoles::REPORTER]);
        $reporterRole->givePermissionTo($chatPermissions
            ->merge($taskPermissions->filter(function ($permission) {
                return in_array($permission, ['view tasks', 'create tasks', 'assign tasks']);
            }))
            ->merge($userPermissions->filter(function ($permission) {
                return $permission === 'view users';
            }))
        );

        $assigneeRole = Role::create(['name' => UserRoles::ASSIGNEE]);
        $assigneeRole->givePermissionTo($chatPermissions
            ->merge($taskPermissions->filter(function ($permission) {
                return $permission === 'view tasks';
            }))
            ->merge($userPermissions->filter(function ($permission) {
                return $permission === 'view users';
            }))
        );
    }
}
