<?php

namespace App\Http\Resources\Admin\V1\Redirect;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class RedirectShowResource extends JsonResource
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
            "destination_url" => $this->destination_url,
            "source_url" => $this->source_url,
            "status_code" => $this->status_code,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
