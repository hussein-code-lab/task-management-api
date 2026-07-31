<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Jobs\SendOverdueTaskNotificationJob;
use App\Models\Task;
use Illuminate\Console\Command;

class CheckOverdueTasks extends Command
{
    protected $signature = 'tasks:check-overdue';

    protected $description = 'Send notifications for overdue tasks';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Task::whereDate('due_date', '<', now())
            ->where('status', '!=', TaskStatus::DONE)
            ->whereNull('overdue_notified_at')
            ->each(function (Task $task) {

                SendOverdueTaskNotificationJob::dispatch($task);

            });

        $this->info('Overdue tasks notifications dispatched.');

        return Command::SUCCESS;
    }
}
