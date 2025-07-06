<?php

namespace App\Http\Resources\Admin\V1\Brand;

use App\Models\Brand;
use App\Models\BrandContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Brand $children
 * @property BrandContent $contents
 * @OA\Schema(
 *     schema="AdminV1BrandShowResource",
 *     title="Brand Resource",
 *     description="Brand resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/BrandContentIndexResource")
 *     ),
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
class BrandShowResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            "parent_name" => $this->parent?->contents->first()->title,
            "children" => BrandListResource::collection($this->children),
            'contents' => BrandContentIndexResource::collection($this->contents),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "type" => $this->type,
            "type_translate" => $this->type_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
