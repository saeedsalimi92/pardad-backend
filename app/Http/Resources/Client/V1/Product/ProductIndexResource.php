<?php

namespace App\Http\Resources\Client\V1\Product;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="ApiV1ProductIndexResource",
 *     title="Admin Product Index Resource",
 *     description="Product listing resource for admin panel",
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
 *         property="en_title",
 *         type="string",
 *         example="Sample English Title"
 *     ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1ProductContentIndexResource")
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"published", "draft", "archived"},
 *         example="published"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         example="Published"
 *     ),
 *     @OA\Property(
 *         property="published_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
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
 *     ),
 *     @OA\Property(
 *         property="props",
 *         type="object",
 *         additionalProperties=true,
 *         example={
 *             "socials": {
 *                 "instagram": "https://instagram.com",
 *                 "telegram": "https://telegram.org"
 *             },
 *             "metas": {
 *                 "keywords": "product, sample",
 *                 "description": "Sample product description"
 *             }
 *         }
 *     )
 * )
 */
class ProductIndexResource extends JsonResource
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
            "code" => $this->code,
            "category_id" => $this->category?->id,
            "category_title" => $this->category?->contents()->first()?->title,
            "category_slug" => $this->category?->contents()->first()?->slug,
            "image" => $this->featuredImage ? url(Storage::url($this->featuredImage->path)) : null,
            "title" => $this->contents?->first()->title ?? "",
            "slug" => $this->contents?->first()->slug ?? "",
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "created_at" => $this->created_at?->toIso8601String(),
            "updated_at" => $this->updated_at?->toIso8601String(),
        ];
    }
}
