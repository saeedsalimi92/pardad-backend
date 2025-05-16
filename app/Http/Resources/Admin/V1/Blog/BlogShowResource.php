<?php

namespace App\Http\Resources\Admin\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property string $en_title
 * @property string $status
 * @property string $status_translate
 * @property string $published_at
 * @property string $created_at
 * @property string $updated_at
 * @property array $props
 * @property mixed $relatedBlogs
 * @OA\Schema(
 *      schema="BlogShowResource",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="title", type="string", example="Sample Blog Title"),
 *      @OA\Property(property="en_title", type="string", example="Sample English Title"),
 *      @OA\Property(property="content", type="string", example="<p>Blog content</p>"),
 *      @OA\Property(property="status", type="string", enum={"draft", "published", "archived"}),
 *      @OA\Property(property="published_at", type="string", format="date-time"),
 *      @OA\Property(property="image_url", type="string", format="url"),
 *      @OA\Property(
 *          property="props",
 *          type="object",
 *          @OA\Property(
 *              property="socials",
 *              type="object",
 *              @OA\Property(property="instagram", type="string"),
 *              @OA\Property(property="telegram", type="string")
 *          ),
 *          @OA\Property(
 *              property="metas",
 *              type="object",
 *              @OA\Property(property="keywords", type="string"),
 *              @OA\Property(property="description", type="string")
 *          )
 *      )
 *  )
 */
class BlogShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "image" => url($this->featuredImage->path),
            "contents" => BlogContentShowResource::collection($this->contents),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "props" => $this->props,
            "related_blogs" => BlogListResource::collection($this->relatedBlogs),
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
