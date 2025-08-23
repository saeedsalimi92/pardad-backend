<?php

namespace App\Http\Requests\Admin\V1\Product;

use App\Enums\Status;
use App\Enums\Locale;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductListRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (auth()->user()->can('list', Product::class)) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', Rule::in(Status::statuses())],
            'locale' => ['nullable', 'string', Rule::in(Locale::allLocale())],
        ];
    }
}
