<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Models\Blog;
use App\Models\Faq;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="BlogFaqIndexRequest",
 *     required={},
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"active", "inactive"},
 *         example="active"
 *     ),
 * )
 */
class BlogFaqIndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (auth()->user()->can('faqIndex', Blog::class)) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return Faq::$indexRule;
    }
}
