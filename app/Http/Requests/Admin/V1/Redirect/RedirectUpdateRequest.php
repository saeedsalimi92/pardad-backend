<?php

namespace App\Http\Requests\Admin\V1\Redirect;

use App\Enums\Status;
use App\Models\Redirect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RedirectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', Redirect::class);
    }

    public function rules(): array
    {
        // مسیرهای غیرمجاز
        $forbiddenSources = [
            '/',
            '/about',
            '/category',
            '/contact',
            '/blog',
        ];

        return [
            'source_url' => [
                'required',
                'string',
                Rule::notIn($forbiddenSources), // جلوگیری از مسیرهای ممنوعه
                Rule::unique('redirects', 'source_url')
                    ->ignore($this->route('redirect'))
                    ->where(fn($q) => $q->whereNull('deleted_at')),
                function ($attribute, $value, $fail) {
                    if ($value === $this->destination_url) {
                        $fail('لینک مبدا و لینک مقصد نمی‌توانند یکسان باشند.');
                    }
                },
            ],
            'destination_url' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value === $this->source_url) {
                        $fail('لینک مقصد و لینک مبدا نمی‌توانند یکسان باشند.');
                    }
                },
            ],
            'status_code' => ['required', 'max:255'],
            'status' => ['required', 'string', Rule::in(Status::statuses())],
        ];
    }

    public function attributes(): array
    {
        return [
            'source_url' => 'لینک قبلی',
            'destination_url' => 'لینک جدید',
            'status_code' => 'کد',
            'status' => 'وضعیت',
        ];
    }
}
