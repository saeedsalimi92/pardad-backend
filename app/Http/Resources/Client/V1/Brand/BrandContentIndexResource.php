<?php

namespace App\Http\Resources\Client\V1\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ApiV1BrandContentIndexResource",
 *     title="Brand Content Resource",
 *     description="Brand content resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *         description="Unique identifier for the content"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Sample Brand Title",
 *         description="Title of the brand content"
 *     ),
 *     @OA\Property(
 *         property="locale",
 *         type="string",
 *         example="fa",
 *         description="Locale/language code of the content"
 *     )
 * )
 */
class BrandContentIndexResource extends JsonResource
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
