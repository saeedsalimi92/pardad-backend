<?php

namespace App\Http\Resources\Admin\V1\Category;

use App\Http\Resources\Admin\V1\Blog\BlogContentIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'contents' => CategoryContentIndexResource::collection($this->contents),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "type" => $this->type,
            "type_translate" => $this->type_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
