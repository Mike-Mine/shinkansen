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
            'delete chat messages'
        ]);

        $userPermissions = collect([
            'view users',
            'update users',
            'delete users'
        ]);

        $taskPermissions = collect([
            'delete tasks',
            'create tasks',
            'assign tasks',
            'view tasks',
            'fulfill tasks',
            'update task status',
            'view deleted tasks',
            'restore tasks',
            'force delete tasks'
        ]);

        $managerRole = Role::create(['name' => UserRoles::MANAGER]);
        $managerRole->givePermissionTo($chatPermissions
            ->merge($taskPermissions->filter(function ($permission) {
                return !in_array($permission, ['view deleted tasks', 'restore tasks', 'force delete tasks']);
            }))
            ->merge($userPermissions->filter(function ($permission) {
                return in_array($permission, ['view users', 'update users']);
            }))
        );

        $reporterRole = Role::create(['name' => UserRoles::REPORTER]);
        $reporterRole->givePermissionTo($chatPermissions
            ->merge($taskPermissions->filter(function ($permission) {
                return in_array($permission, ['view tasks', 'create tasks', 'assign tasks', 'update task status']);
            }))
            ->merge($userPermissions->filter(function ($permission) {
                return $permission === 'view users';
            }))
        );

        $assigneeRole = Role::create(['name' => UserRoles::ASSIGNEE]);
        $assigneeRole->givePermissionTo($chatPermissions
            ->merge($taskPermissions->filter(function ($permission) {
                return in_array($permission, ['view tasks', 'fulfill tasks']);
            }))
            ->merge($userPermissions->filter(function ($permission) {
                return $permission === 'view users';
            }))
        );
    }
}
