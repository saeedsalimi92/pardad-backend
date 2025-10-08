<?php

namespace App\Http\Requests\Client\V1\Product;

use App\Enums\Status;
use App\Enums\Locale;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductListRequest extends FormRequest
{

    public function authorize(): bool
    {

        return false;
    }

    public function rules(): array
    {
        return [];
    }
}
