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
class RedirectShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (auth()->user()->can('show', Redirect::class)) {
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
        return [];
    }

    public function attributes(): array
    {
        return [];
    }
}
