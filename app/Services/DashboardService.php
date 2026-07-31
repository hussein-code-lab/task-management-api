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
                ->where('status', ProjectStatus::ACTIVE)
                ->count(),

            'total_tasks' => $user->tasks()->count(),

            'completed_tasks' => $user->tasks()
                ->where('status', TaskStatus::DONE)
                ->count(),

            'pending_tasks' => $user->tasks()
                ->where('status', '!=', TaskStatus::DONE)
                ->count(),

            'overdue_tasks' => $user->tasks()
                ->whereDate('due_date', '<', now())
                ->where('status', '!=', TaskStatus::DONE)
                ->count(),
        ];
    }
}
