<?php

namespace App\Console\Commands;

use App\Enums\TaskDatesNotificationTypes;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Notifications\TaskDatesNotification;
use Illuminate\Console\Command;

class SendDateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-date-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for task start and due dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasksToStart = Task::where([
            'start_date' => now()->toDateString(),
            'status' => TaskStatus::OPEN
        ])->with('reporter', 'assignee')->get();

        $tasksToFinish = Task::where([
            'due_date' => now()->toDateString(),
        ])->whereNotIn(
            'status', [
                TaskStatus::READY,
                TaskStatus::DONE,
                TaskStatus::CANCELLED
            ]
        )->with('reporter', 'assignee')->get();

        $taskToStartCount = $tasksToStart->count();
        $taskToFinishCount = $tasksToFinish->count();

        if ($taskToStartCount > 0) {
            $this->info("Sending {$taskToStartCount} task start notifications...");
        }

        if ($taskToFinishCount > 0) {
            $this->info("Sending {$taskToFinishCount} task finish notifications...");
        }

        foreach ($tasksToStart as $task) {
            if (empty($task->reporter) || empty($task->assignee)) {
                continue;
            }

            $task->reporter->notify(new TaskDatesNotification($task, TaskDatesNotificationTypes::START));
            $task->assignee->notify(new TaskDatesNotification($task, TaskDatesNotificationTypes::START));
        }

        foreach ($tasksToFinish as $task) {
            if (empty($task->reporter) || empty($task->assignee)) {
                continue;
            }

            $task->reporter->notify(new TaskDatesNotification($task, TaskDatesNotificationTypes::DUE));
            $task->assignee->notify(new TaskDatesNotification($task, TaskDatesNotificationTypes::DUE));
        }
    }
}
