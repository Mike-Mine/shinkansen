<?php

namespace App\Enums;

enum TaskStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case READY = 'ready';
    case CANCELLED = 'cancelled';
    case DONE = 'done';
}
