<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $viewPermissions = [
            'view chat messages',
            'view tasks',
            'view users',
            'view deleted tasks'
        ];

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'isAdmin' => auth()->check() ? $request->user()->hasRole(UserRoles::ADMIN) : false,
                'can' => auth()->check()
                    ? $request->user()->getAllPermissions()
                        ->whereIn('name', $viewPermissions)
                        ->mapWithKeys(fn($permission) => [$permission->name => true])
                        ->all()
                : [],
            ],
        ];
    }
}
