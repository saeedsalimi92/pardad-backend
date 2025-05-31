<?php

namespace App\Http\Requests\Admin\V1\Category;

use App\Enums\CategoryType;
use App\Enums\Locale;
use App\Enums\Status;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="CategoryUpdateRequest",
 *     required={"contents", "status", "type"},
 *          @OA\Property(
 *          property="contents",
 *          type="array",
 *          minItems=2,
 *          maxItems=2,
 *          @OA\Items(
 *              type="object",
 *              required={"locale", "title", "content"},
 *              @OA\Property(property="locale", type="string", enum={"fa", "en"}, example="fa"),
 *              @OA\Property(property="title", type="string", maxLength=255, example="عنوان مقاله"),
 *              @OA\Property(property="content", type="string", example="<p>محتوای مقاله به فارسی</p>"),
 *              @OA\Property(property="slug", type="string", example="blog-title"),
 *              @OA\Property(
 *                  property="props",
 *                  type="object",
 *                  @OA\Property(property="meta_keyword", type="string", maxLength=255, example="کلمات کلیدی"),
 *                  @OA\Property(property="meta_description", type="string", example="توضیحات متا"),
 *                  @OA\Property(property="canonical", type="string", example="http://fa.blog.pardad.com", nullable=true)
 *              )
 *          ),
 *          example={
 *              {
 *                  "locale": "fa",
 *                  "title": "عنوان دسته بندی",
 *                  "slug": "عنوان-مقاله",
 *                  "content": "<p>محتوای دسته بندی فارسی</p>",
 *                  "props": {
 *                      "meta_keyword": "کلمات کلیدی",
 *                      "meta_description": "توضیحات متا",
 *                      "canonical": "http://fa.blog.pardad.com"
 *                  }
 *              },
 *              {
 *                  "locale": "en",
 *                  "title": "Category Title",
 *                  "slug": "blog-title",
 *                  "content": "<p>English content</p>",
 *                  "props": {
 *                      "meta_keyword": "keywords",
 *                      "meta_description": "meta description",
 *                      "canonical": "http://en.blog.pardad.com"
 *                  }
 *              }
 *          }
 *      ),
 *     @OA\Property(
 *          property="parent_id",
 *          type="integer",
 *          example="1"
 *      ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"active", "inactive"},
 *         example="active"
 *     ),
 *    @OA\Property(
 *          property="type",
 *          type="string",
 *          enum={"normal", "expert"},
 *          example="normal"
 *      ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Featured image file (max: 500kb)"
 *     )
 * )
 */
class CategoryUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (auth()->user()->can('update', Category::class)) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [

            'contents' => ['required', 'array', 'min:1'],
            'contents.*' => ['required', 'array'],
            'contents.*.locale' => ['required', Rule::in(Locale::allLocale())],
            'contents.*.title' => ['required', 'string', 'max:255'],
            'contents.*.content' => ['required', 'string'],
            'contents.*.slug' => ['required', 'string', 'max:255'],

            // props as part of each content item
            'contents.*.props' => ['required', 'array'],
            'contents.*.props.meta_keyword' => ['required', 'string', 'max:255'],
            'contents.*.props.meta_description' => ['required', 'string'],
            'contents.*.props.canonical' => ['nullable', 'url'],

            'image' => [
                'bail',
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:500',
            ],

            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],

            'status' => ['required', 'string', Rule::in(Status::statuses())],
            'type' => ['required', 'string', Rule::in(CategoryType::types())],
        ];
    }

    public function attributes(): array
    {
        return [
            'contents.*.title' => 'عنوان',
            'contents.*.content' => 'محتوای',
            'contents.*.props.meta_keyword' => 'کلمات کلیدی متا',
            'contents.*.props.meta_description' => 'توضیحات متا',
            'contents.*.props.canonical' => 'لینک کنونیکال',
        ];
    }

    protected function prepareForValidation(): void
    {
        $contents = $this->input('contents');
        $contents = is_array($contents) ? $contents : ($contents ? json_decode($contents, true) : null);

        $this->merge([
            'contents' => $contents,
        ]);
    }
}
