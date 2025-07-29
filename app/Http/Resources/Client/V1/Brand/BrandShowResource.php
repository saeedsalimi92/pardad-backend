<?php

namespace App\Http\Resources\Client\V1\Brand;

use Illuminate\Support\Facades\Storage;
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
            "image" => $this->featuredImage ? url(Storage::url($this->featuredImage->path)) : null,
            "priority" => $this->priority,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
        ];
    }
}
