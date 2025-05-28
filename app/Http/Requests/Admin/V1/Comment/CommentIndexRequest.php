<?php

namespace App\Http\Requests\Admin\V1\Comment;

use App\Enums\CommentStatus;
use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->user()->can('index', Comment::class)) {
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
            "status" => ["nullable", Rule::in(CommentStatus::statuses())],
            "mobile" => ["nullable", "regex:/^(09)([0-9]{9})$/"]
        ];
    }
}
