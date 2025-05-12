<?php

namespace App\Http\Resources\V1\Admin\Blog;

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
            "title" => $this->title,
            "end_title" => $this->en_title,
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
