<?php

namespace App\Repositories\v1;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function all(): Collection
    {
        return User::orderBy('name')->get();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
