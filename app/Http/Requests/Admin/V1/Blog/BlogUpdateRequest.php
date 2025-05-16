<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="BlogUpdateRequest",
 *     required={"contents"},
 *     @OA\Property(
 *         property="contents",
 *         type="object",
 *         @OA\Property(
 *             property="fa",
 *             type="object",
 *             @OA\Property(property="title", type="string", maxLength=255, example="عنوان مقاله"),
 *             @OA\Property(property="content", type="string", example="<p>محتوای مقاله به فارسی</p>"),
 *         ),
 *         @OA\Property(
 *             property="en",
 *             type="object",
 *             @OA\Property(property="title", type="string", maxLength=255, example="Blog Title"),
 *             @OA\Property(property="content", type="string", example="<p>English blog content</p>"),
 *         )
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"DRAFT", "PUBLISH", "ARCHIVE"},
 *         example="PUBLISH"
 *     ),
 *     @OA\Property(
 *         property="published_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01 12:00:00",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Featured image file (max: 500kb)",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="related_blog_ids",
 *         type="array",
 *         @OA\Items(type="integer"),
 *         example={1, 2, 3},
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="props",
 *         type="object",
 *         @OA\Property(
 *             property="fa",
 *             type="object",
 *             @OA\Property(property="meta_keyword", type="string", maxLength=255, example="کلمات کلیدی"),
 *             @OA\Property(property="meta_description", type="string", example="توضیحات متا فارسی"),
 *             @OA\Property(property="canonical", type="string", example="لینک کنونیکال", nullable=true)
 *         ),
 *         @OA\Property(
 *             property="en",
 *             type="object",
 *             @OA\Property(property="meta_keyword", type="string", maxLength=255, example="Keywords"),
 *             @OA\Property(property="meta_description", type="string", example="English meta description"),
 *             @OA\Property(property="canonical", type="string", example="Canonical link", nullable=true)
 *         ),
 *         nullable=true
 *     )
 * )
 */
class BlogUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $blogId = $this->route('blog');

        return [
            'contents' => ['sometimes', 'array'],
            'contents.fa' => ['sometimes', 'array'],
            'contents.fa.title' => ['sometimes', 'string', 'max:255'],
            'contents.fa.content' => ['sometimes', 'string'],

            'contents.en' => ['sometimes', 'array'],
            'contents.en.title' => ['sometimes', 'string', 'max:255'],
            'contents.en.content' => ['sometimes', 'string'],

            'image' => ['sometimes'],
            'status' => ['sometimes', 'string', Rule::in(Blog::STATUSES)],
            'published_at' => ['sometimes', 'date_format:Y-m-d H:i:s'],

            'related_blog_ids' => ['nullable', 'array'],
            'related_blog_ids.*' => ['exists:blogs,id'],

            'props' => ['nullable', 'array'],
            'props.fa' => ['nullable', 'array'],
            'props.fa.meta_keyword' => ['nullable', 'string'],
            'props.fa.meta_description' => ['nullable', 'string'],
            'props.fa.canonical' => ['nullable', 'string'],

            'props.en' => ['nullable', 'array'],
            'props.en.meta_keyword' => ['nullable', 'string'],
            'props.en.meta_description' => ['nullable', 'string'],
            'props.en.canonical' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'contents.fa.title' => 'عنوان فارسی',
            'contents.fa.content' => 'محتوای فارسی',
            'contents.en.title' => 'عنوان انگلیسی',
            'contents.en.content' => 'محتوای انگلیسی',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'contents' => $this->input('contents') ? json_decode($this->input('contents'), true) : null,
            'props' => $this->input('props') ? json_decode($this->input('props'), true) : null,
            'metas' => $this->input('metas') ? json_decode($this->input('metas'), true) : null,
            'related_blog_ids' => $this->input('related_blog_ids') ? json_decode($this->input('related_blog_ids'), true) : [],
        ]);
    }
}
