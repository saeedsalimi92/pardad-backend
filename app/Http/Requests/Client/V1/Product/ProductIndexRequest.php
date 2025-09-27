<?php

namespace App\Http\Requests\Client\V1\Product;



use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ProductIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "locale" => ['required', 'string', Rule::in(Locale::allLocale())],
            "category_id" => ["bail", "nullable"],
        ];
    }
}
