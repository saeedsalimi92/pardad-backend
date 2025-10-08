<?php

namespace App\Http\Requests\Client\V1\Blog;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *     schema="ApiV1CommentStoreRequest",
 *     required={"first_name", "last_name", "mobile", "content"},
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="User's first name",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         description="User's last name",
 *         example="Snow"
 *     ),
 *     @OA\Property(
 *         property="mobile",
 *         type="string",
 *         description="User's mobile number",
 *         example="09123456789"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         nullable=true,
 *         description="User's email address (optional)",
 *         example="user@example.com"
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         description="Comment content",
 *         example="This is a great article!"
 *     )
 * )
 */
class BlogCommentStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        Log::info('BlogCommentStoreRequest Received', [
            'method' => $this->method(),
            'all_data' => $this->all()
        ]);
    }
    public function rules(): array
    {
        return [
            "first_name" => ['required', 'string', 'max:60'],
            "last_name" => ['required', 'string', 'max:60'],
            "mobile" => ['required', 'string', 'regex:/^(09)([0-9]{9})$/'],
            "email" => ['nullable', 'string', 'email'],
            "content" => ['nullable', 'string'],
        ];
    }
    public function attributes(): array
    {
        return [
            'first_name' => 'لینک فعلی',
            'last_name' => 'لینک جدید',
            'mobile' => 'کد',
            'email' => 'وضعیت',
            'content' => 'محتوا',
        ];
    }
}
