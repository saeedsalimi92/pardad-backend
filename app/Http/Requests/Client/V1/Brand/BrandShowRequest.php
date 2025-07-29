<?php

namespace App\Http\Requests\Client\V1\Brand;

use App\Models\Brand;

use Illuminate\Foundation\Http\FormRequest;

class BrandShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {


        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
