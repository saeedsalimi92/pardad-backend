<?php

namespace App\Http\Requests\Client\V1\Blog;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogListByParentRequest extends FormRequest
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
            'parent_id' => ['required', 'integer', 'exists:blogs,id'],
            'parent_type' => ['required', 'string']
        ];
    }
}
