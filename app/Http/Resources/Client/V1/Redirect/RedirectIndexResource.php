<?php

namespace App\Http\Resources\Client\V1\Redirect;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class RedirectIndexResource extends JsonResource
{
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
