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
     *     )
     *
    Transform the resource into an array.     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
