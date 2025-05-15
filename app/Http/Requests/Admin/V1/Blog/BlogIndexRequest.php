<?php

namespace App\Http\Requests\Admin\V1\Blog;

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
            'per_page' => ['nullable', 'integer'],
        ];
    }
}
