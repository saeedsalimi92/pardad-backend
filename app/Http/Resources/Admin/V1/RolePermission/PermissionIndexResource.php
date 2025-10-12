<?php

namespace App\Http\Resources\Admin\V1\RolePermission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="PermissionIndexResource",
 *      type="object",
 *      title="Permission Resource",
 *      required={"id","name"},
 *      properties={
 *          @OA\Property(property="id", type="integer", example=1),
 *          @OA\Property(property="name", type="string", example="blog.create")
 *      }
 *  )
 * @property mixed $id
 * @property mixed $name
 */
class PermissionIndexResource extends JsonResource
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
            "name" => $this->name
        ];
    }
}
