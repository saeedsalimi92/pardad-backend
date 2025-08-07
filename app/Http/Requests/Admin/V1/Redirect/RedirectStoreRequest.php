<?php

namespace App\Http\Requests\Admin\V1\Redirect;


use App\Enums\Status;

use App\Models\Redirect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $status
 * @property string $status_translate
 */
class RedirectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {

        if (auth()->user()->can('store', Redirect::class)) {
            return true;
        }
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
            'source_url' => ['required', 'string', 'unique:redirects,source_url'],
            'destination_url' => ['required', 'string', 'max:255'],
            'status_code' => ['required',  'max:255'],
            'status' => ['required', 'string', Rule::in(Status::statuses())],

        ];
    }

    public function attributes(): array
    {
        return [
            'source_url' => 'لینک فعلی',
            'destination_url' => 'لینک جدید',
            'status_code' => 'کد',
            'status' => 'وضعیت',
        ];
    }
}
