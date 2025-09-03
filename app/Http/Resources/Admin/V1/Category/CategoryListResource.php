<?php

namespace App\Http\Resources\Admin\V1\Category;

use App\Models\CategoryContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CategoryContent $contents
 * @OA\Schema(
 *     schema="AdminV1CategoryListResource",
 *     title="Category Resource",
 *     description="Category resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *          property="title",
 *          type="string",
 *          example="Sample Title"
 *      ),
 * )
 */
class CategoryListResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->contents?->first()->title ?? "",
            "slug" => $this->contents?->first()->slug ?? "",
        ];
    }
}
