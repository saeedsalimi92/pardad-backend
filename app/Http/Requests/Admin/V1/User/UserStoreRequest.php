<?php

namespace App\Http\Requests\Admin\V1\User;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{


    public function authorize(): bool
    {

        return auth()->user()?->can('store', User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', 'string', 'min:5'],
            'mobile' => ['required', "regex:/^(09)([0-9]{9})$/"],
            'status' => ['required', 'string', Rule::in(Status::statuses())],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'نام',
            'email' => 'ایمیل',
            'password' => 'رمز عبور',
            'status' => 'وضعیت',
            'mobile' => 'موبایل',
        ];
    }
}
