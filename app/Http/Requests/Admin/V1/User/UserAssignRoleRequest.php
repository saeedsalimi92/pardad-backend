<?php

namespace App\Http\Requests\Admin\V1\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $role
 * @property int $user_id
 */
class UserAssignRoleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ];
    }
}
