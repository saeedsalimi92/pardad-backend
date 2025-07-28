<?php

namespace App\Http\Resources\Admin\V1\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AdminV1BrandIndexResource",
 *     title="Brand Resource",
 *     description="Brand resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *
 *
 *     @OA\Property(
 *            property="name",
 *            type="string",
 *            example="brand name"
 *       ),
 *     @OA\Property(
 *            property="priority",
 *            type="string",
 *            example="brand priority"
 *       ),
 *     @OA\Property(
 *            property="file",
 *            type="string",
 *            example="brand file"
 *       ),
 *     @OA\Property(
 *            property="action",
 *            type="string",
 *            example="brand action"
 *       ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Brand status code",
 *         example="active"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         description="Translated status of brand",
 *         example="فعال"
 *     ),
 *
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     )
 * )
 */
class BrandIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "file" => $this->file,
            "action" => $this->action,
            "priority" => $this->priority,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
