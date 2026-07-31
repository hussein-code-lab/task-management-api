<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can create a task', function () {

    $user = User::factory()->create();

    $project = Project::factory()->for($user)->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson("/api/v1/projects/{$project->id}/tasks", [
            'title' => 'Implement Authentication',
            'description' => 'Using Laravel Sanctum',
            'priority' => TaskPriority::HIGH->value,
            'status' => TaskStatus::TODO->value,
        ]);

    $response->assertCreated();

    $response->assertJsonStructure([
        'message',
        'data' => [
            'id',
            'project_id',
            'title',
            'description',
            'priority',
            'status',
            'due_date',
        ],
    ]);

    $this->assertDatabaseHas('tasks', [
        'project_id' => $project->id,
        'title' => 'Implement Authentication',
    ]);
});

test('user can list project tasks', function () {

    $user = User::factory()->create();

    $project = Project::factory()->for($user)->create();

    Task::factory()->count(3)->for($project)->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}/tasks");

    $response->assertOk();

    $response->assertJsonStructure([
        'data',
        'links',
        'meta',
    ]);
});

test('user can view a task', function () {

    $user = User::factory()->create();

    $project = Project::factory()->for($user)->create();

    $task = Task::factory()->for($project)->create();

    $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}/tasks/{$task->id}")
        ->assertOk();
});

test('user can update a task', function () {

    $user = User::factory()->create();

    $project = Project::factory()->for($user)->create();

    $task = Task::factory()->for($project)->create();

    $this
        ->actingAs($user, 'sanctum')
        ->putJson("/api/v1/projects/{$project->id}/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'status' => TaskStatus::DONE->value,
        ])
        ->assertOk();

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated Task',
        'status' => TaskStatus::DONE->value,
    ]);
});

test('user can delete a task', function () {

    $user = User::factory()->create();

    $project = Project::factory()->for($user)->create();

    $task = Task::factory()->for($project)->create();

    $this
        ->actingAs($user, 'sanctum')
        ->deleteJson("/api/v1/projects/{$project->id}/tasks/{$task->id}")
        ->assertOk();

    $this->assertSoftDeleted('tasks', [
        'id' => $task->id,
    ]);
});

test('user can filter tasks by status', function () {

    $user = User::factory()->create();

    $project = Project::factory()->for($user)->create();

    Task::factory()->for($project)->create([
        'status' => TaskStatus::TODO,
    ]);

    Task::factory()->for($project)->create([
        'status' => TaskStatus::DONE,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}/tasks?status=todo");

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

test('user can filter tasks by priority', function () {

    $user = User::factory()->create();

    $project = Project::factory()->for($user)->create();

    Task::factory()->for($project)->create([
        'priority' => TaskPriority::LOW,
    ]);

    Task::factory()->for($project)->create([
        'priority' => TaskPriority::HIGH,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}/tasks?priority=high");

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

test('user can search tasks by title', function () {

    $user = User::factory()->create();

    $project = Project::factory()->for($user)->create();

    Task::factory()->for($project)->create([
        'title' => 'Laravel API',
    ]);

    Task::factory()->for($project)->create([
        'title' => 'React Frontend',
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}/tasks?search=Laravel");

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

test('user cannot access another users project tasks', function () {

    $owner = User::factory()->create();

    $anotherUser = User::factory()->create();

    $project = Project::factory()->for($owner)->create();

    Task::factory()->for($project)->create();

    $this
        ->actingAs($anotherUser, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}/tasks")
        ->assertForbidden();
});
