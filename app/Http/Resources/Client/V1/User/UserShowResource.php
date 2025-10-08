<?php

namespace App\Http\Resources\Client\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserShowResource",
 *     type="object",
 *     title="User Show Resource",
 *     description="User details resource",
 *     required={"id", "name", "email", "status", "status_translate"},
 *     @OA\Property(property="id", type="integer", example=1, description="User ID"),
 *     @OA\Property(property="name", type="string", example="Seo Manager", description="User full name"),
 *     @OA\Property(property="email", type="string", example="user@example.com", description="User email address"),
 *     @OA\Property(property="status", type="integer", example=1, description="User status (1 = active, 0 = inactive)"),
 *     @OA\Property(property="status_translate", type="string", example="Active", description="Translated user status")
 * )
 */
class UserShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
        ];
    }
}
