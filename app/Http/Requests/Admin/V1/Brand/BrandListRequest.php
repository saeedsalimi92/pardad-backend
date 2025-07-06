<?php

namespace App\Http\Requests\Admin\V1\Brand;

use App\Enums\BrandScope;
use App\Enums\BrandType;
use App\Enums\Locale;
use App\Enums\Status;
use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandListRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (auth()->user()->can('list', Brand::class)) {
            return true;
        }

        return false;
    }

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
