<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class UserResource extends JsonResource
{
    /**
     *
     *
     * @OA\Schema(
     *      schema="UserResource",
     *      type="object",
     *      @OA\Property(
     *        property="name",
     *        type="string",
     *      ),
     *      @OA\Property(
     *         property="email",
     *         type="string",
     *       ),
     *     @OA\Property(
     *          property="created_at",
     *          type="date",
     *        ),
     *     )
     *
     * Transform the resource into an array.     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => date_format($this->created_at, 'Y-m-d H:i'),
        ];
    }
}
