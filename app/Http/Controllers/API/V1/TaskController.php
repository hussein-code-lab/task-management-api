<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(public TaskService $taskService) {}

    public function index(Project $project, Request $request)
    {
        $this->authorize('view', $project);

        $tasks = $this->taskService->getAll($project, $request->all());

        return TaskResource::collection($tasks);
    }

    public function store(Project $project, StoreTaskRequest $request)
    {
        $this->authorize('update', $project);

        $task = $this->taskService->create($project, $request->validated());

        return response()->json([
            'message' => 'Task created successfully.',
            'data' => new TaskResource($task),
        ], 201);
    }

    public function show(Project $project, Task $task)
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(Project $project, Task $task, UpdateTaskRequest $request)
    {
        $this->authorize('update', $task);

        $task = $this->taskService->update($task, $request->validated());

        return response()->json([
            'message' => 'Task updated successfully.',
            'data' => new TaskResource($task),
        ]);
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }
}
