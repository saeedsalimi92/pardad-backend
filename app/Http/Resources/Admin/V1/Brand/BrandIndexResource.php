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
 *    @OA\Property(
 *          property="parent_id",
 *          type="integer",
 *          example=1
 *      ),
 *     @OA\Property(
 *           property="parent_name",
 *           type="string",
 *           example="Main brand title"
 *       ),
 *     @OA\Property(
 *            property="title",
 *            type="string",
 *            example="brand title"
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
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Brand type code",
 *         example="expert"
 *     ),
 *     @OA\Property(
 *         property="type_translate",
 *         type="string",
 *         description="Translated type of brand",
 *         example="حرفه ای"
 *     ),
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
            "parent_id" => $this->parent_id,
            "parent_name" => $this->parent?->contents->first()?->title,
            "title" => $this->contents->first()->title ?? "",
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "type" => $this->type,
            "type_translate" => $this->type_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
