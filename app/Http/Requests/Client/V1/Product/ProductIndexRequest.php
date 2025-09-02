<?php

namespace App\Http\Requests\Client\V1\Product;



use Illuminate\Foundation\Http\FormRequest;


class ProductIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

        ];
    }
}
