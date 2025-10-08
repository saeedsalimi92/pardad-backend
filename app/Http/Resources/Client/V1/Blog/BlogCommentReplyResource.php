<?php

namespace App\Http\Resources\Client\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $content
 * @property mixed $created_at
 * @property mixed $updated_at
 * @OA\Schema(
 *      schema="ApiV1BlogCommentReplyResource",
 *      title="Blog Comment Reply Resource",
 *      description="Reply to a blog comment resource representation",
 *      type="object",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          format="int64",
 *          example=1
 *      ),
 *      @OA\Property(
 *          property="first_name",
 *          type="string",
 *          maxLength=50,
 *          example="John"
 *      ),
 *      @OA\Property(
 *          property="last_name",
 *          type="string",
 *          maxLength=50,
 *          example="Snow"
 *      ),
 *      @OA\Property(
 *          property="content",
 *          type="string",
 *          description="The reply content",
 *          example="This is a reply to the comment"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          example="2023-01-01T12:00:00Z"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="date-time",
 *          example="2023-01-01T12:00:00Z"
 *      )
 *  )
 */
class BlogCommentReplyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "content" => $this->content,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
