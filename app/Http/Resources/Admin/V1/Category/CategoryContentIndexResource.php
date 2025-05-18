<?php

namespace App\Http\Resources\Admin\V1\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CategoryContentIndexResource",
 *     title="Category Content Resource",
 *     description="Category content resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *         description="Unique identifier for the content"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Sample Category Title",
 *         description="Title of the category content"
 *     ),
 *     @OA\Property(
 *         property="locale",
 *         type="string",
 *         example="fa",
 *         description="Locale/language code of the content"
 *     )
 * )
 */
class CategoryContentIndexResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "locale" => $this->locale
        ];
    }
}
