<?php

namespace App\Http\Resources\Client\V1\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ApiV1ProductContentIndexResource",
 *     title="Product Content Index Resource",
 *     description="Basic product content information",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Sample Product Title"
 *     ),
 *     @OA\Property(
 *         property="locale",
 *         type="string",
 *         minLength=2,
 *         maxLength=5,
 *         example="en"
 *     )
 * )
 */
class ProductContentIndexResource extends JsonResource
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
