<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Services\v1\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $login = $this->authService->login($request->validated());

        if (! $login) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data' => $login,
            'message' => 'Login successful',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'status' => true,
            'message' => 'Logged out',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $request->user(),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $changed = $this->authService->changePassword(
            $request->user(),
            $request->validated('current_password'),
            $request->validated('password')
        );

        if (! $changed) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect',
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => 'Password updated',
        ]);
    }
}
