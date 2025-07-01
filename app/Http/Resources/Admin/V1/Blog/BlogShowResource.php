<?php

namespace App\Http\Resources\Admin\V1\Blog;

use App\Http\Resources\Admin\V1\Category\CategoryShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AdminV1BlogShowResource",
 *     title="Admin Blog Show Resource",
 *     description="Detailed blog resource for admin panel with all related data",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="url",
 *         example="https://example.com/storage/blog/images/featured.jpg"
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
 *         @OA\Items(ref="#/components/schemas/AdminV1BlogContentShowResource")
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"draft", "published", "archived"},
 *         example="published"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         example="Published"
 *     ),
 *     @OA\Property(
 *         property="props",
 *         type="object",
 *         @OA\Property(
 *             property="socials",
 *             type="object",
 *             @OA\Property(
 *                 property="instagram",
 *                 type="string",
 *                 example="https://instagram.com"
 *             ),
 *             @OA\Property(
 *                 property="telegram",
 *                 type="string",
 *                 example="https://telegram.org"
 *             )
 *         ),
 *         @OA\Property(
 *             property="metas",
 *             type="object",
 *             @OA\Property(
 *                 property="keywords",
 *                 type="string",
 *                 example="blog, sample"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 example="Sample blog description"
 *             )
 *         )
 *     ),
 *     @OA\Property(
 *         property="related_blogs",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AdminV1BlogListResource")
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
 *     )
 * )
 */
class BlogShowResource extends JsonResource
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
            "category_id" => $this->category?->id,
            "category_parent_id" => $this->category?->parent_id,
            "category_title" => $this->category?->contents()->first()?->title,
            "image" => url($this?->featuredImage?->path),
            "contents" => BlogContentShowResource::collection($this->contents),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "related_blogs" => BlogListResource::collection($this->relatedBlogs),
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
