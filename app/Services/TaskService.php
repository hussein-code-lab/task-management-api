<?php

namespace App\Services;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function create(Project $project, array $data): Task
    {
        return $project->tasks()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'priority' => $data['priority'] ?? TaskPriority::MEDIUM,
            'status' => $data['status'] ?? TaskStatus::TODO,
            'due_date' => $data['due_date'] ?? null,
        ]);

    }

    public function getAll(Project $project, array $filters): LengthAwarePaginator
    {
        return $project->tasks()
            ->when(
                $filters['status'] ?? null,
                fn ($query, $status) => $query->where('status', $status)
            )
            ->when(
                $filters['priority'] ?? null,
                fn ($query, $priority) => $query->where('priority', $priority)
            )
            ->when(
                $filters['search'] ?? null,
                fn ($query, $search) => $query->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
            )
            ->latest()
            ->paginate(10);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
