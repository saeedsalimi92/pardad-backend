<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Models\Blog;
use App\Models\Faq;
use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="BlogFaqStoreRequest",
 *     required={"question", "answer", "status"},
 *     @OA\Property(
 *          property="question",
 *          type="string",
 *          example="Question"
 *      ),
 *     @OA\Property(
 *           property="answer",
 *           type="string",
 *           example="Answer"
 *       ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"active", "inactive"},
 *         example="active"
 *     ),
 * )
 */
class BlogFaqStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (auth()->user()->can('faqStore', Blog::class)) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return Faq::$createRule;
    }
}
