<?php

namespace App\Http\Resources\Admin\V1\Category;

use App\Models\CategoryContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CategoryContent $contents
 * @OA\Schema(
 *     schema="CategoryListResource",
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
 *         property="type",
 *         type="string",
 *         description="Category type code",
 *         example="expert"
 *     ),
 *     @OA\Property(
 *         property="type_translate",
 *         type="string",
 *         description="Translated type of category",
 *         example="حرفه ای"
 *     ),
 * )
 */
class CategoryListResource extends JsonResource
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
            "title" => $this->contents?->first()->title ?? "",
            "type" => $this->type,
            "type_translate" => $this->type_translate,
        ];
    }
}
