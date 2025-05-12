<?php

namespace App\Http\Requests\V1\Admin\Blog;

use App\Models\Blog;
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
            'status' => ['nullable', 'string', Rule::in(Blog::STATUSES)],
            'parent_id' => ['nullable', 'integer', 'exists:blogs,id'],
            'parent_type' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer'],
        ];
    }
}
