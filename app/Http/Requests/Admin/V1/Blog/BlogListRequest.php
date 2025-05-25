<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Enums\BlogStatus;
use App\Enums\Locale;
use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogListRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', Rule::in(BlogStatus::statuses())],
            'locale' => ['nullable', 'string', Rule::in(Locale::allLocale())],
        ];
    }
}
