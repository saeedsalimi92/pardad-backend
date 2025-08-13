<?php

namespace App\Http\Resources\Admin\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *       schema="UserIndexResuorce",
 *       type="object",
 *       title="User Resource",
 *       required={"id","name"},
 *       properties={
 *           @OA\Property(property="id", type="integer", example=1),
 *           @OA\Property(property="name", type="string", example="seo manager")
 *       }
 *   )
 * @property mixed $id
 * @property mixed $name
 */
class UserIndexResource extends JsonResource
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
            "status" => $this->status,
            "status_translate" => $this->status_translate,
        ];
    }
}
