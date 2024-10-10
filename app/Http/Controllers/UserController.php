<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Users/Index', [
            'users' => User::when(
                $request->search, function ($query) use ($request) {
                    $query
                        ->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                }
            )->paginate(10)
                ->withQueryString(),
            'searchQuery' => $request->search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => $user->load('roles', 'permissions'),
            'allRoles' => Role::all(),
            'can' => [
                'update' => Gate::allows('update users', $user),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        if (
            $user->is(auth()->user())
            && $user->hasRole(UserRoles::ADMIN)
            && !in_array(UserRoles::ADMIN, $request->roles)
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'roles' => 'sometimes | array | exists:roles,name',
        ]);

        $user->syncRoles($validated['roles']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
