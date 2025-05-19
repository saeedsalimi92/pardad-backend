<?php

namespace App\Http\Resources\Admin\V1\Faq;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @param  \Illuminate\Http\Request  $request
 *  @return array
 * /
 *@property mixed $answer
 * @property mixed $status
 * @property mixed $status_translate
 * @property mixed $created_at
 * @property mixed $updated_at
 * @OA\Schema(
 *      schema="FaqShowResource",
 *      type="object",
 *      description="FAQ resource representation",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          format="int64",
 *          example=1,
 *          description="Unique identifier for the FAQ"
 *      ),
 *      @OA\Property(
 *          property="question",
 *          type="string",
 *          example="How can I leave a comment?",
 *          description="The FAQ question"
 *      ),
 *      @OA\Property(
 *          property="answer",
 *          type="string",
 *          example="By filling in the information and clicking the register button",
 *          description="The FAQ answer"
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string",
 *          example="published",
 *          enum={"draft", "published", "archived"},
 *          description="Current status of the FAQ"
 *      ),
 *      @OA\Property(
 *          property="status_translate",
 *          type="string",
 *          example="منتشر شده",
 *          description="Translated status for display purposes"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          example="2023-01-01T12:00:00Z",
 *          description="Creation timestamp"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="date-time",
 *          example="2023-01-02T15:30:00Z",
 *          description="Last update timestamp"
 *      )
 *  )
 */
class FaqShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "question" => $this->question,
            "answer" => $this->answer,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
