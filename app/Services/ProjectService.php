<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function create(array $data): Project
    {
        return Project::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);
    }

    public function getAll()
    {
        return auth()->user()
            ->projects()
            ->latest()
            ->paginate(10);
    }

    public function getById(Project $project): Project
    {
        return $project;
    }

    public function update(Project $project, array $data): bool
    {
        return $project->update($data);
    }

    public function delete(Project $project): bool
    {
        return $project->delete();
    }
}
