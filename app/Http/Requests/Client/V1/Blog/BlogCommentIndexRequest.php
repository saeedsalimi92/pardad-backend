<?php

namespace App\Http\Requests\Client\V1\Blog;

use Illuminate\Foundation\Http\FormRequest;

class BlogCommentIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
