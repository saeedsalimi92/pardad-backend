<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Enums\BlogStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'category_id' => ['bail', 'nullable', 'integer', 'exists:categories,id'],
            'status' => ['nullable', 'string', Rule::in(BlogStatus::statuses())],
            'per_page' => ['nullable', 'integer'],
        ];
    }
}
