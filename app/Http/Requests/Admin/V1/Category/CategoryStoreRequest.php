<?php

namespace App\Http\Requests\Admin\V1\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CategoryStoreRequest",
 *     required={"contents", "image", "status"},
 *     @OA\Property(
 *         property="contents",
 *         type="object",
 *         @OA\Property(
 *             property="fa",
 *             type="object",
 *             required={"title", "content"},
 *             @OA\Property(property="title", type="string", maxLength=255, example="عنوان مقاله"),
 *             @OA\Property(property="content", type="string", example="<p>محتوای دسته بندی به فارسی</p>"),
 *         ),
 *         @OA\Property(
 *             property="en",
 *             type="object",
 *             required={"title", "content"},
 *             @OA\Property(property="title", type="string", maxLength=255, example="Blog Title"),
 *             @OA\Property(property="content", type="string", example="<p>English category content</p>"),
 *         )
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"ACTIVE", "INACTIVE"},
 *         example="INACTIVE"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Featured image file (max: 500kb)"
 *     ),
 *    @OA\Property(
 *          property="props",
 *          type="object",
 *          @OA\Property(
 *              property="fa",
 *              type="object",
 *              @OA\Property(property="meta_keyword", type="string", maxLength=255, example="عنوان مقاله"),
 *              @OA\Property(property="meta_description", type="string", example="محتوای مقاله به فارسی"),
 *              @OA\Property(property="canonical", type="string", example="http://www.category.pardad.com", nullable=true)
 *          ),
 *          @OA\Property(
 *              property="en",
 *              type="object",
 *              @OA\Property(property="title", type="string", maxLength=255, example="Blog Title"),
 *              @OA\Property(property="content", type="string", example="<p>English blog content</p>"),
 *              @OA\Property(property="description", type="string", example="http://www.category.pardad.com", nullable=true)
 *          )
 *      )
 *     )
 * )
 */
class CategoryStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['nullable'],

            'contents' => ['required', 'array'],
            'contents.fa' => ['required', 'array'],
            'contents.fa.title' => ['required', 'string', 'max:255'],
            'contents.fa.content' => ['required', 'string'],

            'contents.en' => ['required', 'array'],
            'contents.en.title' => ['required', 'string', 'max:255'],
            'contents.en.content' => ['required', 'string'],

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
        ]);
    }
}
