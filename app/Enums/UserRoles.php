<?php

namespace App\Enums;

enum UserRoles: string
{
    case ADMIN = 'Admin';
    case USER = 'User';
    case MANAGER = 'Manager';
    case ASSIGNEE = 'Assignee';
    case REPORTER = 'Reporter';
}
