<?php

namespace App\Http\Requests\Admin\V1\Product;

use App\Enums\Status;
use App\Enums\CategoryType;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->user()->can('index', Product::class)
            || auth()->user()->can('super_index', Product::class);
    }


    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'category_id' => ['bail', 'nullable', 'integer', 'exists:categories,id'],
            'status' => ['nullable', 'string', Rule::in(Status::statuses())],
            'per_page' => ['nullable', 'integer'],
            'type' => ['nullable', Rule::in(CategoryType::types())],
        ];
    }
}
