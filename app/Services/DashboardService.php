<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\User;

class DashboardService
{
    public function index(User $user): array
    {
        return [
            'total_projects' => $user->projects()->count(),

            'active_projects' => $user->projects()
                ->where('projects.status', ProjectStatus::ACTIVE)
                ->count(),

            'total_tasks' => $user->tasks()->count(),

            'completed_tasks' => $user->tasks()
                ->where('tasks.status', TaskStatus::DONE)
                ->count(),

            'pending_tasks' => $user->tasks()
                ->where('tasks.status', '!=', TaskStatus::DONE)
                ->count(),

            'overdue_tasks' => $user->tasks()
                ->whereDate('tasks.due_date', '<', now())
                ->where('tasks.status', '!=', TaskStatus::DONE)
                ->count(),
        ];
    }
}
