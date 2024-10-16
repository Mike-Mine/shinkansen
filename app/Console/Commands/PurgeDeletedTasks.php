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
        $tasksToDeleteCount = Task::withTrashed()->where('deleted_at', '<', now()->subDays(30))->count();

        if ($tasksToDeleteCount === 0) {
            $this->info('No tasks to delete');
        } else {
            $this->info("Deleting {$tasksToDeleteCount} tasks...");
        }

        Task::withTrashed()->where('deleted_at', '<', now()->subDays(30))->forceDelete();
    }
}
