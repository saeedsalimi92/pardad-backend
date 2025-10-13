<?php

namespace App\Http\Resources\Client\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $code
 * @OA\Schema(
 *     schema="ApiV1BlogListResource",
 *     title="Blog List Resource",
 *     description="Minimal blog representation for listing purposes",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Sample Blog Title"
 *     ),
 *     @OA\Property(
 *         property="en_title",
 *         type="string",
 *         example="Sample English Title"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"draft", "published", "archived"},
 *         example="published"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         example="Published"
 *     ),
 *     @OA\Property(
 *         property="published_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1BlogContentIndexResource")
 *     )
 * )
 */
class BlogListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->code,
            'title' => $this->title,
            'en_title' => $this->en_title,
            'status' => $this->status,
            'status_translate' => $this->status_translate,
            'published_at' => $this->published_at?->toIso8601String(),
            'contents' => BlogContentIndexResource::collection($this->whenLoaded('contents'))
        ];
    }
}
