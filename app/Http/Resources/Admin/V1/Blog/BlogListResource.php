<?php

namespace App\Http\Resources\Admin\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $contents
 * @property mixed $id
 * @OA\Schema(
 *     schema="AdminV1BlogListResource",
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
            "id" => $this->id,
            "title" => $this->contents?->first()->title ?? "",
        ];
    }
}
