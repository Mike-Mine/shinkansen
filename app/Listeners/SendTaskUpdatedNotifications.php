<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Notifications\TaskUpdated as NotificationsTaskUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTaskUpdatedNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskUpdated $event): void
    {
        $reporter = $event->task->reporter;
        $assignee = $event->task->assignee;

        if ($reporter && $reporter->id !== $event->task->user_id) {
            $reporter->notify(new NotificationsTaskUpdated($event->task));
        }

        if ($assignee && $assignee->id !== $event->task->user_id) {
            $assignee->notify(new NotificationsTaskUpdated($event->task));
        }
    }
}
