<?php

namespace App\Http\Resources\Client\V1\Blog;

use App\Http\Resources\Admin\V1\Blog\BlogContentIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogIndexResource extends JsonResource
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
            'contents' => BlogContentIndexResource::collection($this->contents),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
