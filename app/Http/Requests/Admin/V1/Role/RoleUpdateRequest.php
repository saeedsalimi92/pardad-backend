<?php

namespace App\Http\Requests\Admin\V1\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 */
class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->user()->can('update', Role::class)) {
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
            "name" => ['required', 'string'],
            "display_name" => ['required', 'string'],
        ];
    }
}
