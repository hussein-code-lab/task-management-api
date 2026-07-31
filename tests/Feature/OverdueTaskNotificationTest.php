<?php

use App\Enums\TaskStatus;
use App\Jobs\SendOverdueTaskNotificationJob;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('overdue tasks dispatch notification job', function () {

    Queue::fake();

    $user = User::factory()->create();

    $project = Project::factory()
        ->for($user)
        ->create();

    Task::factory()
        ->for($project)
        ->create([
            'status' => TaskStatus::TODO,
            'due_date' => now()->subDay(),
            'overdue_notified_at' => null,
        ]);

    $this->artisan('tasks:check-overdue')
        ->assertSuccessful();

    Queue::assertPushed(
        SendOverdueTaskNotificationJob::class
    );
});

test('overdue task job sends notification to user', function () {

    $user = User::factory()->create();

    $project = Project::factory()
        ->for($user)
        ->create();

    $task = Task::factory()
        ->for($project)
        ->create([
            'status' => TaskStatus::TODO,
            'due_date' => now()->subDay(),
            'overdue_notified_at' => null,
        ]);

    SendOverdueTaskNotificationJob::dispatchSync($task);

    expect($user->notifications()->count())
        ->toBe(1);

    expect($user->notifications()->first()->data)
        ->toMatchArray([
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
});

test('overdue task notification is not sent twice', function () {

    Queue::fake();

    $user = User::factory()->create();

    $project = Project::factory()
        ->for($user)
        ->create();

    $task = Task::factory()
        ->for($project)
        ->create([
            'status' => TaskStatus::TODO,
            'due_date' => now()->subDay(),
            'overdue_notified_at' => null,
        ]);

    // First run
    $this->artisan('tasks:check-overdue')
        ->assertSuccessful();

    Queue::assertPushed(
        SendOverdueTaskNotificationJob::class,
        1
    );

    // Simulate job completion
    $task->update([
        'overdue_notified_at' => now(),
    ]);

    Queue::fake();

    // Second run
    $this->artisan('tasks:check-overdue')
        ->assertSuccessful();

    Queue::assertNotPushed(
        SendOverdueTaskNotificationJob::class
    );
});
