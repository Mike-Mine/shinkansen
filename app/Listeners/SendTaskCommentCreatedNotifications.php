<?php

namespace App\Listeners;

use App\Events\TaskCommentCreated;
use App\Notifications\NewTaskComment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTaskCommentCreatedNotifications implements ShouldQueue
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
    public function handle(TaskCommentCreated $event): void
    {
        $reporter = $event->comment->task->reporter;
        $assignee = $event->comment->task->assignee;

        if ($reporter && $reporter->id !== $event->comment->user_id) {
            $reporter->notify(new NewTaskComment($event->comment));
        }

        if ($assignee && $assignee->id !== $event->comment->user_id) {
            $assignee->notify(new NewTaskComment($event->comment));
        }
    }
}
