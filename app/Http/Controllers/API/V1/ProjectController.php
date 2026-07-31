<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    public function __construct(public ProjectService $projectService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = $this->projectService->getAll();

        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Project created successfully.',
            'data' => new ProjectResource($project),
        ], 201);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $this->projectService->update(
            $project,
            $request->validated()
        );

        return response()->json([
            'message' => 'Project updated successfully.',
            'data' => new ProjectResource($project),
        ]);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $this->projectService->delete($project);

        return response()->json([
            'message' => 'Project deleted successfully.',
        ]);
    }
}
