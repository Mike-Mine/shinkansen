<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class PurgeDeletedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-deleted-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge deleted tasks older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasksToDelete = Task::withTrashed()->where('deleted_at', '<', now()->subDays(30));
        $tasksToDeleteCount = $tasksToDelete->count();

        $message = match ($tasksToDeleteCount) {
            0 => 'No tasks to delete',
            1 => 'Deleting 1 task...',
            default => "Deleting {$tasksToDeleteCount} tasks...",
        };

        $this->info($message);

        $tasksToDelete->forceDelete();
    }
}
