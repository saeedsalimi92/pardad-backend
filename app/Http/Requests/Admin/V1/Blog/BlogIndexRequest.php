<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Enums\BlogStatus;
use App\Enums\CategoryType;
use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->user()->can('index', Blog::class)
            || auth()->user()->can('super_index', Blog::class);
    }


    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'category_id' => ['bail', 'nullable', 'integer', 'exists:categories,id'],
            'status' => ['nullable', 'string', Rule::in(BlogStatus::statuses())],
            'per_page' => ['nullable', 'integer'],
            'type' => ['nullable', Rule::in(CategoryType::types())],
        ];
    }
}
