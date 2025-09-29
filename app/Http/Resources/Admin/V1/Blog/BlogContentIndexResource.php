<?php

namespace App\Http\Resources\Admin\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AdminV1BlogContentIndexResource",
 *     title="Blog Content Index Resource",
 *     description="Basic blog content information",
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
 *         property="locale",
 *         type="string",
 *         minLength=2,
 *         maxLength=5,
 *         example="en"
 *     )
 * )
 */
class BlogContentIndexResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "locale" => $this->locale
        ];
    }
}
