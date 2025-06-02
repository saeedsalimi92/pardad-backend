<?php

namespace App\Http\Resources\Client\V1\Blog;

use App\Http\Resources\Client\V1\Category\CategoryContentShowResource;
use App\Http\Resources\Client\V1\Category\CategoryShowResource;
use App\Http\Resources\Client\V1\Faq\FaqIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $featuredImage
 * @property mixed $id
 * @property mixed $status
 * @property mixed $status_translate
 * @property mixed $props
 * @property mixed $published_at
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $code
 * @OA\Schema(
 *     schema="ApiV1BlogShowResource",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="url",
 *         example="https://example.com/storage/images/blog/featured.jpg"
 *     ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1BlogContentShowResource")
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
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
 *         example={"key": "value"}
 *     ),
 *     @OA\Property(
 *         property="related_blogs",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1BlogListResource")
 *     ),
 *     @OA\Property(
 *         property="faq",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1FaqIndexResource")
 *     ),
 *     @OA\Property(
 *         property="published_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-01T12:00:00Z"
 *     )
 * )
 */
class BlogShowResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->code,
            "category" => CategoryContentShowResource::make($this->category?->contents?->first()),
            "image" => url($this?->featuredImage?->path),
            "contents" => BlogContentShowResource::collection($this->contents),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "related_blogs" => BlogListResource::collection($this->relatedBlogs),
            "faq" => FaqIndexResource::collection($this->faqs),
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
