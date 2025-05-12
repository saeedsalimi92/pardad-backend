<?php

namespace App\Http\Requests\V1\Admin\Blog;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Blog $blog
 */
class BlogUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:blogs,title,'.$this->blog],
            'status' => ['required', 'string', Rule::in(Blog::STATUSES)],
            'type' => ['nullable', 'string', 'max:255'],
            'canonical' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'parent_type' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer'],
            'related_blog_ids' => ['nullable', 'array'],
            'published_at' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
