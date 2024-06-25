<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Builder
    {
        return Post::query();
    }

    public function create(array $data): Post
    {
        return Post::query()->create($data);
    }

    public function update(array $data, $id): Post
    {
        $user = Post::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id): void
    {
        $user = Post::findOrFail($id);
        $user->delete();
    }

    public function find($id): Post
    {
        return Post::findOrFail($id);
    }

}
