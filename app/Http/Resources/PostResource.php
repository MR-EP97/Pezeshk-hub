<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'title' => $this->title,
                'content' => $this->content,
                'created_at' => date_format($this->created_at, 'Y-m-d H:i'),

                'author' => UserResource::collection($this->whenLoaded('user'))

        ];
    }
}
