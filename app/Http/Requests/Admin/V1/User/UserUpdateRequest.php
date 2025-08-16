<?php

namespace App\Http\Requests\Admin\V1\User;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{


    public function authorize(): bool
    {

        return auth()->user()?->can('update', User::class) ?? false;
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
                Rule::unique('users', 'email')->ignore($this->route('id')),
            ],
            'mobile' => [
                'required',
                'regex:/^(09)([0-9]{9})$/',
                Rule::unique('users', 'mobile')
                    ->ignore($this->route('user')?->id ?? $this->route('id'))
            ],
            'status' => ['required', 'string', Rule::in(Status::statuses())],
            'password' => ['prohibited'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'نام',
            'email' => 'ایمیل',
            'password' => 'رمز عبور',
            'mobile' => 'موبایل',
            'status' => 'وضعیت',
        ];
    }
}
