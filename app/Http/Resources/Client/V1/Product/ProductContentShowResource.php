<?php

namespace App\Http\Resources\Client\V1\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ApiV1ProductContentShowResource",
 *     title="Product Content Show Resource",
 *     description="Detailed product content resource for admin panel",
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
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         description="HTML content of the product post",
 *         example="<p>This is the product content</p>"
 *     )
 * )
 */
class ProductContentShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "locale" => $this->locale,
            "content" => $this->content,
            "props" => $this->props
        ];
    }
}
