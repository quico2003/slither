<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\v1\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->userService->all(),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => 'User created',
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user = $this->userService->update($user, $request->validated());

        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => 'User updated',
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->delete($user);

        return response()->json([
            'status' => true,
            'message' => 'User deleted',
        ]);
    }
}
