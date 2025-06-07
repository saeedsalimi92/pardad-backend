<?php

namespace App\Http\Resources\Admin\V1\Blog;

use App\Http\Resources\Admin\V1\Category\CategoryListResource;
use App\Http\Resources\Admin\V1\Category\CategoryShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AdminV1BlogIndexResource",
 *     title="Admin Blog Index Resource",
 *     description="Blog listing resource for admin panel",
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
 *         example="Sample Blog Title"
 *     ),
 *     @OA\Property(
 *         property="en_title",
 *         type="string",
 *         example="Sample English Title"
 *     ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AdminV1BlogContentIndexResource")
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
 *                 "keywords": "blog, sample",
 *                 "description": "Sample blog description"
 *             }
 *         }
 *     )
 * )
 */
class BlogIndexResource extends JsonResource
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
            "category_id" => $this->category?->contents()->first()?->id,
            "category_title" => $this->category?->contents()->first()?->title,
            "title" => $this->contents?->first()->title ?? "",
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "published_at" => $this->published_at?->toIso8601String(),
            "created_at" => $this->created_at?->toIso8601String(),
            "updated_at" => $this->updated_at?->toIso8601String(),
        ];
    }
}
