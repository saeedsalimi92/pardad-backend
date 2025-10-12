<?php

namespace App\Http\Resources\Client\V1\Faq;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $question
 * @property mixed $answer
 * @OA\Schema(
 *     schema="ApiV1FaqIndexResource",
 *     title="FAQ Index Resource",
 *     description="FAQ resource representation for listing",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="question",
 *         type="string",
 *         example="How can I reset my password?"
 *     ),
 *     @OA\Property(
 *         property="answer",
 *         type="string",
 *         example="You can reset your password using the 'Forgot Password' link on the login page."
 *     )
 * )
 */
class FaqIndexResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "question" => $this->question,
            "answer" => $this->answer
        ];
    }
}
