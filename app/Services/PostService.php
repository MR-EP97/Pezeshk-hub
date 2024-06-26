<?php

namespace App\Services;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostService
{
    public function __construct(
        protected PostRepositoryInterface $postRepository
    )
    {
    }

    public function create(array $data): Post
    {
        return $this->postRepository->create($data);
    }

    public function update(array $data, $id): Post
    {
        return $this->postRepository->update($data, $id);
    }

    public function delete($id): void
    {
         $this->postRepository->delete($id);
    }

    public function all(int $perPage = 10)
    {
        return $this->postRepository->all()->paginate();
    }

    public function find($id): Post
    {
        return $this->postRepository->find($id);
    }

}
