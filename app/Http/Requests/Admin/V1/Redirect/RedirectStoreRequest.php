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

        $forbiddenSources = [
            '/',
            '/about',
            '/category',
            '/contact',
            '/insight-guides',
            '/industrial-brands',
        ];
        return [
            'source_url' => ['required', 'string',
                Rule::notIn($forbiddenSources),
                Rule::unique('redirects', 'source_url')
                    ->where(fn($q) => $q->whereNull('deleted_at')),
                function ($attribute, $value, $fail) {
                    if ($value === $this->destination_url) {
                        $fail('لینک مبدا و لینک مقصد نمی‌توانند یکسان باشند.');
                    }
                },
            ],
            'destination_url' => ['required', 'string', 'max:255',

                function ($attribute, $value, $fail) {
                    if ($value === $this->source_url) {
                        $fail('لینک مقصد و لینک مبدا نمی‌توانند یکسان باشند.');
                    }
                }
            ],
            'status_code' => ['required', 'max:255'],
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
