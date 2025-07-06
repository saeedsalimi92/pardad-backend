<?php

namespace App\Http\Resources\Client\V1\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandContentShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "category_id" => $this->category->code,
            "title" => $this->title,
            "slug" => $this->slug,
            "props" => $this->props
        ];
    }
}
