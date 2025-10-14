<?php

namespace App\Http\Resources\Client\V1\Category;

use App\Models\CategoryContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property CategoryContent $contents
 * @property mixed $code
 * @OA\Schema(
 *     schema="ApiV1CategoryChildrenResource",
 *     title="Category Resource",
 *     description="Category resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1CategoryContentIndexResource")
 *     ),
 * )
 */
class CategoryChildrenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "code" => $this->code,
            "title" => $this->when(
                $this->relationLoaded('contents') && $this->contents->isNotEmpty(),
                fn() => $this->contents->first()->title,
                ""
            ),
            "contents" => CategoryContentIndexResource::collection($this->whenLoaded('contents')),
            "parent_id" => $this->parent_id,
            "parent_name" => $this->when(
                $this->relationLoaded('parent') && $this->parent?->relationLoaded('contents'),
                fn() => $this->parent?->contents->first()?->title
            ),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "image" => $this->featuredImage ? url(Storage::url($this->featuredImage->path)) : null,
            "short_desc" =>  $this->contents?->first()->props['short_desc'] ?? "",
            "type" => $this->type,
            "priority" => $this->priority,
            "slug" => $this->when(
                $this->relationLoaded('contents') && $this->contents->isNotEmpty(),
                fn() => $this->contents->first()->slug,
                ""
            ),
            "type_translate" => $this->type_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
