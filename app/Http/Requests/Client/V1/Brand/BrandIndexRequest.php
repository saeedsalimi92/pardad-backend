<?php

namespace App\Http\Requests\Client\V1\Brand;

use App\Enums\BrandScope;
use App\Enums\BrandType;
use App\Enums\Locale;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "type" => ["nullable", Rule::in(BrandType::types())],
            "scope" => ["nullable", Rule::in(BrandScope::scopes())],
            "locale" => ["nullable", Rule::in(Locale::allLocale())],
            "parent_id" => ["bail", "nullable", "integer", "exists:brands,id"],
        ];
    }
}
