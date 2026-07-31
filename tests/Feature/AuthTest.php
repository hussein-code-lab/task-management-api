<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can register', function () {

    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Hussein',
        'email' => 'hussein@example.com',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
    ]);

    $response->assertStatus(201);

    $response->assertJsonStructure([
        'message',
        'user' => [
            'id',
            'name',
            'email',
            'created_at',
        ],
        'token',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'hussein@example.com',
    ]);

});

test('user can login', function () {

    $user = User::factory()->create([
        'email' => 'hussein@example.com',
        'password' => bcrypt('Password@123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'hussein@example.com',
        'password' => 'Password@123',
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'message',
        'user' => [
            'id',
            'name',
            'email',
            'created_at',
        ],
        'token',
    ]);

});

test('user can logout', function () {

    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this
        ->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/v1/auth/logout');

    $response->assertStatus(200);

    $response->assertJson([
        'message' => 'Logged out successfully.',
    ]);

});
