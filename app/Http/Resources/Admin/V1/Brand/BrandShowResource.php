<?php

namespace App\Http\Resources\Admin\V1\Brand;

use App\Models\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class BrandShowResource extends JsonResource
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
            "name" => $this->name,
            "file" => $this->file,
            "action" => $this->action,
            "image" => url($this?->featuredImage?->path),
            "priority" => $this->priority,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
