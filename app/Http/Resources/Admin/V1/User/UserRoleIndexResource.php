<?php

namespace App\Http\Resources\Admin\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserRoleIndexResource",
 *     type="object",
 *     title="User Role Index Resource",
 *     required={"id", "name", "email", "status", "mobile"},
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="SEO Manager"),
 *         @OA\Property(property="email", type="string", format="email", example="seo@example.com"),
 *         @OA\Property(property="status", type="string", example="active"),
 *         @OA\Property(property="mobile", type="string", example="09123456789"),
 *         @OA\Property(property="status_translate", type="string", example="فعال")
 *     }
 * )
 *
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $status
 * @property mixed $mobile
 * @property mixed $status_translate
 */
class UserRoleIndexResource extends JsonResource
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
            "display_name" => $this->display_name,
        ];
    }
}
