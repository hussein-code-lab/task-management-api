<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOverdueTaskNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->task->project->user;

        $user->notify(new TaskOverdueNotification($this->task));

        $this->task->update([
            'overdue_notified_at' => now(),
        ]);
    }
}
