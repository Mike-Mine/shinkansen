<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function (User $user) {
    return $user->hasPermissionTo('view chat messages');
});

Broadcast::channel('task.{id}', function (User $user) {
    return $user->hasPermissionTo('view tasks');
});
