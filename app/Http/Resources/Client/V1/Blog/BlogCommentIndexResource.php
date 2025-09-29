<?php

namespace App\Http\Resources\Client\V1\Blog;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $content
 * @property mixed $replies
 * @property mixed $created_at
 * @property mixed $updated_at
 * @OA\Schema(
 *     schema="ApiV1BlogCommentIndexResource",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         example="Snow"
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         example="This is a great article!"
 *     ),
 *     @OA\Property(
 *         property="replies",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1BlogCommentReplyResource")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-01T12:00:00Z"
 *     )
 * )
 */
class BlogCommentIndexResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "content" => $this->content,
            "replies" => BlogCommentReplyResource::collection($this->replies),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
