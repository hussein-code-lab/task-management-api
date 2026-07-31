<?php

use App\Enums\ProjectStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can create a project', function () {

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/v1/projects', [
            'name' => 'Laravel API',
            'description' => 'Task management system API',
            'status' => ProjectStatus::ACTIVE->value,
        ]);

    $response->assertStatus(201);

    $response->assertJsonStructure([
        'message',
        'data' => [
            'id',
            'name',
            'description',
            'status',
        ],
    ]);

    $this->assertDatabaseHas('projects', [
        'name' => 'Laravel API',
        'user_id' => $user->id,
    ]);

});

test('user can list his projects', function () {

    $user = User::factory()->create();

    $user->projects()->createMany([
        [
            'name' => 'Project One',
            'description' => 'First project',
            'status' => ProjectStatus::ACTIVE->value,
        ],
        [
            'name' => 'Project Two',
            'description' => 'Second project',
            'status' => ProjectStatus::COMPLETED->value,
        ],
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson('/api/v1/projects');

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'description',
                'status',
            ],
        ],
        'links',
        'meta',
    ]);

    expect($response->json('data'))
        ->toHaveCount(2);

});

test('user can view a project', function () {

    $user = User::factory()->create();

    $project = $user->projects()->create([
        'name' => 'Laravel API',
        'description' => 'Project description',
        'status' => ProjectStatus::ACTIVE->value,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}");

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'description',
            'status',
        ],
    ]);

});

test('user can update a project', function () {

    $user = User::factory()->create();

    $project = $user->projects()->create([
        'name' => 'Old Name',
        'description' => 'Old description',
        'status' => ProjectStatus::ACTIVE->value,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->putJson("/api/v1/projects/{$project->id}", [
            'name' => 'Updated Project',
            'status' => ProjectStatus::COMPLETED->value,
        ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'Updated Project',
        'status' => ProjectStatus::COMPLETED->value,
    ]);

});

test('user can delete a project', function () {

    $user = User::factory()->create();

    $project = $user->projects()->create([
        'name' => 'Project To Delete',
        'description' => 'Delete me',
        'status' => ProjectStatus::ACTIVE->value,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->deleteJson("/api/v1/projects/{$project->id}");

    $response->assertStatus(200);

    $this->assertSoftDeleted('projects', [
        'id' => $project->id,
    ]);

});

test('user cannot access another users project', function () {

    $user = User::factory()->create();

    $anotherUser = User::factory()->create();

    $project = $anotherUser->projects()->create([
        'name' => 'Private Project',
        'description' => 'Not yours',
        'status' => ProjectStatus::ACTIVE->value,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/v1/projects/{$project->id}");

    $response->assertStatus(403);

});
