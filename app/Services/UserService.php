<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function update(array $data, $id): User
    {
        return $this->userRepository->update($data, $id);
    }

    public function delete($id): User
    {
        return $this->userRepository->delete($id);
    }

    public function all(): User
    {
        return $this->userRepository->all();
    }

    public function find($id): User
    {
        return $this->userRepository->find($id);
    }
}
