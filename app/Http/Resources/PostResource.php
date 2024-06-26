<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class PostResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="PostResource",
     *     type="object",
     *     @OA\Property(
     *       property="title",
     *       type="string",
     *     ),
     *     @OA\Property(
     *        property="content",
     *        type="string",
     *      ),
     *     @OA\Property(
     *        property="created_at",
     *        type="date",
     *      ),
     * )
     *
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
        ];
    }
}
