<?php

namespace App\Http\Resources\Client\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ApiV1BlogContentShowResource",
 *     title="Blog Content Show Resource",
 *     description="Detailed blog content resource for admin panel",
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
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         description="HTML content of the blog post",
 *         example="<p>This is the blog content</p>"
 *     )
 * )
 */
class BlogContentShowResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "locale" => $this->locale,
            "content" => $this->content
        ];
    }
}
