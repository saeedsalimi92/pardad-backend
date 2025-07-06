<?php

namespace App\Http\Requests\Admin\V1\Brand;

use App\Enums\BrandScope;
use App\Enums\BrandType;
use App\Enums\Locale;
use App\Enums\Status;
use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->user()->can('index', Brand::class)) {
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
            "status" => ["nullable", Rule::in(Status::statuses())],
            "type" => ["nullable", Rule::in(BrandType::types())],
            "scope" => ["nullable", Rule::in(BrandScope::scopes())],
            "locale" => ["nullable", Rule::in(Locale::allLocale())],
        ];
    }
}
