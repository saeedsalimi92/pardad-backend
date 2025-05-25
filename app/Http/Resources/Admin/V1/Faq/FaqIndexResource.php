<?php

namespace App\Http\Resources\Admin\V1\Faq;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $question
 * @property mixed $answer
 * @property mixed $status
 * @property mixed $status_translate
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class FaqIndexResource extends JsonResource
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
            "updated_at" => $this->updated_at,
        ];
    }
}
