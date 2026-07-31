<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => new UserResource($data['user']),
            'token' => $data['token'],
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        return response()->json([
            'message' => 'Login successful.',
            'user' => new UserResource($data['user']),
            'token' => $data['token'],
        ]);
    }

    public function logout(Request $request)
    {
        $data = $this->authService->logout($request->user());

        return response()->json($data);
    }
}
