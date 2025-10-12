<?php

namespace App\Http\Requests\Admin\V1\ActivityLog;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Activitylog\Models\Activity;

class ActivityLogIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     public function authorize(): bool
    {
        if (auth()->user()->can('index', Activity::class)) {
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
            //
        ];
    }
}
