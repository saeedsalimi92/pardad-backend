<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="BlogStoreRequest",
 *     required={"contents", "image", "status", "published_at"},
 *     @OA\Property(
 *         property="contents",
 *         type="object",
 *         @OA\Property(
 *             property="fa",
 *             type="object",
 *             required={"title", "content"},
 *             @OA\Property(property="title", type="string", maxLength=255, example="عنوان مقاله"),
 *             @OA\Property(property="content", type="string", example="<p>محتوای مقاله به فارسی</p>"),
 *         ),
 *         @OA\Property(
 *             property="en",
 *             type="object",
 *             required={"title", "content"},
 *             @OA\Property(property="title", type="string", maxLength=255, example="Blog Title"),
 *             @OA\Property(property="content", type="string", example="<p>English blog content</p>"),
 *         )
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"DRAFT", "PUBLISH", "ARCHIVE"},
 *         example="DRAFT"
 *     ),
 *     @OA\Property(
 *         property="published_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Featured image file (max: 500kb)"
 *     ),
 *     @OA\Property(
 *         property="related_blog_ids",
 *         type="array",
 *         @OA\Items(type="integer"),
 *         example={1, 2, 3}
 *     ),
 *    @OA\Property(
 *          property="props",
 *          type="object",
 *          @OA\Property(
 *              property="fa",
 *              type="object",
 *              @OA\Property(property="meta_keyword", type="string", maxLength=255, example="عنوان مقاله"),
 *              @OA\Property(property="meta_description", type="string", example="محتوای مقاله به فارسی"),
 *              @OA\Property(property="canonical", type="string", example="http://www.blog.pardad.com", nullable=true)
 *          ),
 *          @OA\Property(
 *              property="en",
 *              type="object",
 *              @OA\Property(property="title", type="string", maxLength=255, example="Blog Title"),
 *              @OA\Property(property="content", type="string", example="<p>English blog content</p>"),
 *              @OA\Property(property="description", type="string", example="http://www.blog.pardad.com", nullable=true)
 *          )
 *      ),
 * )
 */
class BlogStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contents' => ['required', 'array'],
            'contents.fa' => ['required', 'array'],
            'contents.fa.title' => ['required', 'string', 'max:255'],
            'contents.fa.content' => ['required', 'string'],

            'contents.en' => ['required', 'array'],
            'contents.en.title' => ['required', 'string', 'max:255'],
            'contents.en.content' => ['required', 'string'],

            'image' => ['required'],
            'status' => ['required', 'string', Rule::in(Blog::STATUSES)],
            'published_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'related_blog_ids' => ['nullable', 'array'],
            'related_blog_ids.*' => ['exists:blogs,id'],

            'props' => ['nullable', 'array'],

            'props.fa' => ['nullable', 'array'],
            'props.fa.meta_keyword' => ['nullable', 'string'],
            'props.fa.meta_description' => ['nullable', 'string'],
            'props.fa.canonical' => ['nullable', 'url'],

            'props.en' => ['nullable', 'array'],
            'props.en.meta_keyword' => ['nullable', 'string'],
            'props.en.meta_description' => ['nullable', 'string'],
            'props.en.canonical' => ['nullable', 'url'],
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
            'related_blog_ids' => (is_string($this->related_blog_ids) ? explode(",", $this->related_blog_ids) : $this->related_blog_ids)
        ]);
    }

}
