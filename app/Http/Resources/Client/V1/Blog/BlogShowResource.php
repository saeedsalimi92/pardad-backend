<?php

namespace App\Http\Resources\Client\V1\Blog;

use App\Http\Resources\Admin\V1\Blog\BlogContentShowResource;
use App\Http\Resources\Admin\V1\Blog\BlogListResource;
use App\Http\Resources\Client\V1\FaqIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed|null $featuredImage
 * @property mixed $contents
 * @property mixed $status
 * @property mixed $status_translate
 * @property mixed $props
 * @property mixed $relatedBlogs
 * @property mixed $faqs
 * @property mixed $published_at
 * @property mixed $created_at
 * @property mixed $updated_at
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
        return array(
            "id" => $this->id,
            "image" => url($this?->featuredImage?->path),
            "contents" => BlogContentShowResource::collection($this->contents),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "props" => $this->props,
            "related_blogs" => BlogListResource::collection($this->relatedBlogs),
            "faq" => FaqIndexResource::collection($this->faqs),
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        );
    }
}
