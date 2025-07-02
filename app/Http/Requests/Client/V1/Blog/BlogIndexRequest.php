<?php

namespace App\Http\Requests\Client\V1\Blog;

use App\Enums\Locale;
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
            "locale" => ["required", Rule::in(Locale::allLocale())],
            "category_id" => ["bail", "nullable", "exists:categories,code"],
        ];
    }
}
