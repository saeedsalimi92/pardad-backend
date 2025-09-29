<?php

namespace App\Http\Requests\Admin\V1\User;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('change_password', User::class);
    }

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'min:5',
                'confirmed'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'رمز عبور',
            'password_confirmation' => 'تایید رمز عبور',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => __('validation.required', ['attribute' => $this->attributes()['password']]),
            'password.min' => __('validation.min.string', ['attribute' => $this->attributes()['password'], 'min' => 5]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => $this->attributes()['password']]),
            'password.regex' => 'رمز عبور باید حداقل شامل یک حرف بزرگ، یک حرف کوچک، یک عدد و یک کاراکتر خاص باشد.',
        ];
    }
}
