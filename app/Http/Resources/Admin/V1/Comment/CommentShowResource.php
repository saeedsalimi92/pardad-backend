<?php

namespace App\Http\Resources\Admin\V1\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="CommentShowResource",
 *      type="object",
 *      description="Comment resource representation for index listing",
 *      title="Comment Index Resource",
 *      @OA\Property(
 *          property="full_name",
 *          type="string",
 *          example="John snow",
 *          description="Full name of the comment author"
 *      ),
 *      @OA\Property(
 *          property="mobile",
 *          type="string",
 *          example="09123456789",
 *          description="Mobile number of the comment writer"
 *      ),
 *      @OA\Property(
 *           property="email",
 *           type="string",
 *           example="example@gmail.com",
 *           description="User email"
 *       ),
 *      @OA\Property(
 *          property="status",
 *          type="string",
 *          example="approved",
 *          enum={"pending", "approved", "rejected"},
 *          description="Current status of the comment"
 *      ),
 *      @OA\Property(
 *          property="status_translate",
 *          type="string",
 *          example="تایید شده",
 *          description="Translated status for display purposes"
 *      ),
 *      @OA\Property(
 *           property="comment",
 *           type="string",
 *           example="This is the new comment from user",
 *           description="Comment body"
 *       ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          example="2023-01-01T12:00:00Z",
 *          description="Creation timestamp of the comment"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="date-time",
 *          example="2023-01-02T15:30:00Z",
 *          description="Last update timestamp of the comment"
 *      )
 *  )
 * @property mixed $full_name
 * @property mixed $mobile
 * @property mixed $status
 * @property mixed $status_translate
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $comment
 * @property mixed $email
 * @property mixed $commentable
 * @property mixed $content
 */
class CommentShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "entity_id" => $this->commentable->id,
            "entity_title" => $this->commentable->contents?->first()->title,
            "full_name" => $this->full_name,
            "content" => $this->content,
            "mobile" => $this->mobile,
            "email" => $this->email,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "replies" => CommentReplyResource::collection($this->replies),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
