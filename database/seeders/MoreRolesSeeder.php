<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class MoreRolesSeeder extends Seeder
{
    private $chatPermissions;

    private $userPermissions;

    private $taskPermissions;

    public function __construct()
    {
        $this->chatPermissions = collect([
            'view chat messages',
            'create chat messages',
            'update chat messages',
            'delete chat messages'
        ]);

        $this->userPermissions = collect([
            'view users',
            'update users',
            'delete users'
        ]);

        $this->taskPermissions = collect([
            'delete tasks',
            'create tasks',
            'assign tasks',
            'view tasks',
            'fulfill tasks',
            'update task status',
            'view deleted tasks',
            'restore tasks',
            'force delete tasks',
            'change reporter'
        ]);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managerRole = Role::create(['name' => UserRoles::MANAGER]);
        $managerRole->givePermissionTo($this->chatPermissions
            ->merge($this->taskPermissions->filter(function ($permission) {
                return !in_array($permission, ['view deleted tasks', 'restore tasks', 'force delete tasks']);
            }))
            ->merge($this->userPermissions->filter(function ($permission) {
                return in_array($permission, ['view users', 'update users']);
            }))
        );

        $reporterRole = Role::create(['name' => UserRoles::REPORTER]);
        $reporterRole->givePermissionTo($this->chatPermissions
            ->merge($this->taskPermissions->filter(function ($permission) {
                return in_array($permission, ['view tasks', 'create tasks', 'assign tasks', 'update task status']);
            }))
            ->merge($this->userPermissions->filter(function ($permission) {
                return $permission === 'view users';
            }))
        );

        $assigneeRole = Role::create(['name' => UserRoles::ASSIGNEE]);
        $assigneeRole->givePermissionTo($this->chatPermissions
            ->merge($this->taskPermissions->filter(function ($permission) {
                return in_array($permission, ['view tasks', 'fulfill tasks']);
            }))
            ->merge($this->userPermissions->filter(function ($permission) {
                return $permission === 'view users';
            }))
        );
    }
}
