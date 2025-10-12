<?php

namespace App\Http\Requests\Admin\V1\Comment;

use App\Models\Comment;
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
        if (auth()->user()->can('reply', Comment::class)) {
            return true;
        }

        return false;
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
