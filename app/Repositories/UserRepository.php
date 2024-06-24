<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all(): User
    {
        return User::all();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(array $data, $id): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function find($id): User
    {
        return User::findOrFail($id);
    }
}
