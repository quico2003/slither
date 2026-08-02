<?php

namespace App\Services\v1;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $input): array|false
    {
        $user = User::where('email', $input['email'])->first();

        if (! $user || ! Hash::check($input['password'], $user->password)) {
            return false;
        }

        $token = $user->createToken('portal-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (! Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update(['password' => $newPassword]);

        return true;
    }
}
