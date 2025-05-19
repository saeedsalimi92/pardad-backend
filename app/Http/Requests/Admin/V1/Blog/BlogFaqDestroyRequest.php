<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Models\Faq;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="BlogFaqDestroyRequest",
 *     required={},
 * )
 */
class BlogFaqDestroyRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Faq::$destroyRule;
    }
}
