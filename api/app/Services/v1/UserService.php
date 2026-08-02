<?php

namespace App\Services\v1;

use App\Models\User;
use App\Repositories\v1\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function all(): Collection
    {
        return $this->userRepository->all();
    }

    public function create(array $input): User
    {
        return $this->userRepository->create($input);
    }

    public function update(User $user, array $input): User
    {
        if (empty($input['password'])) {
            unset($input['password']);
        }

        return $this->userRepository->update($user, $input);
    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
