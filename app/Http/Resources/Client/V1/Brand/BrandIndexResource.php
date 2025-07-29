<?php

namespace App\Http\Resources\Client\V1\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class BrandIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "file" => $this->file,
            "action" => $this->action,
            "priority" => $this->priority,
            "image" => $this->featuredImage ? url(Storage::url($this->featuredImage->path)) : null,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
        ];
    }
}
