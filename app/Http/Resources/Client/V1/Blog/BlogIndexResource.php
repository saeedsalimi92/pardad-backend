<?php

namespace App\Http\Resources\Client\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $title
 * @property mixed $en_title
 * @property mixed $status
 * @property mixed $status_translate
 * @property mixed $published_at
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $contents
 * @property mixed $category_id
 * @property mixed $code
 * @OA\Schema(
 *     schema="ApiV1BlogIndexResource",
 *     title="Blog Index Resource",
 *     description="Paginated list of blog posts",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
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
 *         @OA\Items(ref="#/components/schemas/ApiV1BlogContentIndexResource")
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
 *         property="props",
 *         type="object",
 *         additionalProperties=true,
 *         example={
 *             "socials": {"instagram": "https://instagram.com", "telegram": "https://telegram.org"},
 *             "metas": {"keywords": "blog, sample", "description": "Sample blog description"}
 *         }
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
class BlogIndexResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->code,
            "title" => $this->contents?->first()->title ?? "",
            "category_id" => $this->category_id,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
