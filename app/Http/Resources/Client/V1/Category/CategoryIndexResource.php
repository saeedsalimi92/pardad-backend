<?php

namespace App\Http\Resources\Client\V1\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $contents
 * @property mixed $parent_id
 * @property mixed $parent
 * @property mixed $status
 * @property mixed $status_translate
 * @property mixed $type
 * @property mixed $type_translate
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $code
 * @OA\Schema(
 *     schema="CategoryIndexResource",
 *     title="Category Index Resource",
 *     description="Category resource representation",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Main Category"
 *     ),
 *     @OA\Property(
 *         property="parent_id",
 *         type="integer",
 *         nullable=true,
 *         example=null
 *     ),
 *     @OA\Property(
 *         property="parent_name",
 *         type="string",
 *         nullable=true,
 *         example="Parent Category"
 *     ),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1CategoryListResource")
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"active", "inactive", "archived"},
 *         example="active"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         example="فعال"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         example="blog"
 *     ),
 *     @OA\Property(
 *         property="type_translate",
 *         type="string",
 *         example="بلاگ"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     )
 * )
 * @method relationLoaded(string $string)
 */
class CategoryIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->code,
            "title" => $this->when(
                $this->relationLoaded('contents') && $this->contents->isNotEmpty(),
                fn() => $this->contents->first()->title,
                ""
            ),
            "parent_id" => $this->parent_id,
            "parent_name" => $this->when(
                $this->relationLoaded('parent') && $this->parent?->relationLoaded('contents'),
                fn() => $this->parent?->contents->first()?->title
            ),
            "children" => CategoryListResource::collection($this->whenLoaded('children')),
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "type" => $this->type,
            "type_translate" => $this->type_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
