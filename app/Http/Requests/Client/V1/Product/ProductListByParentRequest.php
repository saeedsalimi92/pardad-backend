<?php

namespace App\Http\Requests\Client\V1\Product;

use App\Enums\Status;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductListByParentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in(Status::statuses())],
            'parent_id' => ['required', 'integer', 'exists:blogs,id'],
            'parent_type' => ['required', 'string']
        ];
    }
}
