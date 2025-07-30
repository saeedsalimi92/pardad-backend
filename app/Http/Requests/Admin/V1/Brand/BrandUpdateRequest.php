<?php

namespace App\Http\Requests\Admin\V1\Brand;

use App\Enums\Status;
use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        if (auth()->user()->can('update', Brand::class)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'action' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'string', 'max:255'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:5000',
            ],
            'priority' => ['nullable', 'integer'],
            'status' => ['required', 'string', Rule::in(Status::statuses())],

        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'عنوان',
            'file' => 'فایل',
            'action' => 'لینک',
            'status' => 'وضعیت',
        ];
    }
}
