<?php

namespace App\Http\Requests\Client\V1\Category;

use App\Enums\CategoryScope;
use App\Enums\CategoryType;
use App\Enums\Locale;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "type" => ["nullable", Rule::in(CategoryType::types())],
            "scope" => ["nullable", Rule::in(CategoryScope::scopes())],
            "locale" => ["nullable", Rule::in(Locale::allLocale())],
        ];
    }
}
