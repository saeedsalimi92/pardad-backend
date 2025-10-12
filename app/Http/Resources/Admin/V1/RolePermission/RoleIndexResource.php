<?php

namespace App\Http\Resources\Admin\V1\RolePermission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *       schema="RoleIndexResource",
 *       type="object",
 *       title="Permission Resource",
 *       required={"id","name"},
 *       properties={
 *           @OA\Property(property="id", type="integer", example=1),
 *           @OA\Property(property="name", type="string", example="seo manager")
 *       }
 *   )
 * @property mixed $id
 * @property mixed $name
 */
class RoleIndexResource extends JsonResource
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
