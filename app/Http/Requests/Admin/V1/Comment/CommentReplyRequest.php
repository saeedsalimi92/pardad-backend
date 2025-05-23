<?php

namespace App\Http\Requests\Admin\V1\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CommentReplyRequest",
 *     required={"content"},
 *     @OA\Property(
 *         property="content",
 *         type="string"
 *     )
 * )
 */
class CommentReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "content" => ['required', 'string'],
        ];
    }
}
