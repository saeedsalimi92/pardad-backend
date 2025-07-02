<?php

namespace App\Http\Requests\Admin\V1\BentoLayout;

use App\Models\BentoLayout;
use Illuminate\Foundation\Http\FormRequest;

class BentoLayoutUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->user()->can('update', BentoLayout::class)) {
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
            "param" => ['required', 'array'],
        ];
    }
}
