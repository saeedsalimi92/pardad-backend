<?php

namespace App\Http\Resources\Client\V1\Category;

use App\Models\CategoryContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CategoryContent $contents
 * @property mixed $code
 * @OA\Schema(
 *     schema="ApiV1CategoryListResource",
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
 * )
 */
class CategoryListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->code,
            "title" => $this->contents?->first()->title ?? "",
            "slug" => $this->contents?->first()->slug ?? "",
        ];
    }
}
