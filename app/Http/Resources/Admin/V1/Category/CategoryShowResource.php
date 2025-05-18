<?php

namespace App\Http\Resources\Admin\V1\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CategoryShowResource",
 *     title="Category Resource",
 *     description="Category resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CategoryContentIndexResource")
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Category status code",
 *         example="active"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         description="Translated status of category",
 *         example="فعال"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Category type code",
 *         example="EXPERT"
 *     ),
 *     @OA\Property(
 *         property="type_translate",
 *         type="string",
 *         description="Translated type of category",
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
class CategoryShowResource extends JsonResource
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
            'contents' => CategoryContentIndexResource::collection($this->contents),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "type" => $this->type,
            "type_translate" => $this->type_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
