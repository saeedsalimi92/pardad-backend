<?php

namespace App\Http\Requests\Admin\V1\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $role
 * @property int $user_id
 */
class RoleAssignRequest extends FormRequest
{

    public function authorize(): bool
    {
       /* if (auth()->user()->can('assign_to_user', Role::class)) {
            return true;
        }*/

        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['sometimes', 'string', 'exists:roles,name'],
        ];
    }
}
