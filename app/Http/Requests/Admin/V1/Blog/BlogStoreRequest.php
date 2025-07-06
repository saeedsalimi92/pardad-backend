<?php

namespace App\Http\Requests\Admin\V1\Blog;

use App\Enums\BlogStatus;
use App\Enums\Locale;
use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="BlogStoreRequest",
 *     required={"contents", "status", "published_at"},
 *     @OA\Property(
 *          property="category_id",
 *          type="integer",
 *          example="1"
 *      ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         minItems=2,
 *         maxItems=2,
 *         @OA\Items(
 *             type="object",
 *             required={"locale", "title", "content"},
 *             @OA\Property(property="locale", type="string", enum={"fa", "en"}, example="fa"),
 *             @OA\Property(property="title", type="string", maxLength=255, example="عنوان مقاله"),
 *             @OA\Property(property="content", type="string", example="<p>محتوای مقاله به فارسی</p>"),
 *             @OA\Property(property="slug", type="string", example="blog-title"),
 *             @OA\Property(
 *                 property="props",
 *                 type="object",
 *                 @OA\Property(property="meta_keyword", type="string", maxLength=255, example="کلمات کلیدی"),
 *                 @OA\Property(property="meta_title", type="string", maxLength=255, example="عنوان متا"),
 *                 @OA\Property(property="meta_description", type="string", example="توضیحات متا"),
 *                 @OA\Property(property="canonical", type="string", example="http://fa.blog.pardad.com", nullable=true),
 *                 @OA\Property(property="no_index", type="boolean", example="true", nullable=true),
 *                 @OA\Property(property="short_desc", type="string", example="short_desc", nullable=true)
 *             )
 *         ),
 *         example={
 *             {
 *                 "locale": "fa",
 *                 "title": "عنوان مقاله",
 *                 "slug": "عنوان-مقاله",
 *                 "content": "<p>محتوای فارسی</p>",
 *                 "props": {
 *                     "meta_keyword": "کلمات کلیدی",
 *                     "meta_title": "عنوان مقاله",
 *                     "meta_description": "توضیحات متا",
 *                     "canonical": "http://fa.blog.pardad.com",
 *                     "no_index": false,
 *                     "short_desc": "خلاصه بلاگ"
 *                 }
 *             },
 *             {
 *                 "locale": "en",
 *                 "title": "Blog Title",
 *                 "slug": "blog-title",
 *                 "content": "<p>English content</p>",
 *                 "props": {
 *                     "meta_keyword": "keywords",
 *                     "meta_title": "Blog Title",
 *                     "meta_description": "meta description",
 *                     "canonical": "http://en.blog.pardad.com",
 *                     "no_index": false,
 *                     "short_desc": "Short Desc"
 *                 }
 *             }
 *         }
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"draft", "published", "archived"},
 *         example="draft"
 *     ),
 *          @OA\Property(
 *          property="props",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              @OA\Property(
 *                  property="bento_layouts",
 *                  type="array",
 *                  @OA\Items(
 *                      type="object",
 *                      @OA\Property(property="i", type="string", example="0"),
 *                      @OA\Property(property="x", type="integer", example=8),
 *                      @OA\Property(property="y", type="integer", example=1),
 *                      @OA\Property(property="w", type="integer", example=2),
 *                      @OA\Property(property="h", type="integer", example=4),
 *                      @OA\Property(property="component", type="string", example="Panel1"),
 *                      @OA\Property(property="noResize", type="boolean", example=false),
 *                      @OA\Property(property="noMove", type="boolean", example=false),
 *                      @OA\Property(property="dashboard", type="string", example="user"),
 *                      @OA\Property(property="showMore", type="boolean", example=false),
 *                      @OA\Property(property="hasTitle", type="boolean", example=false),
 *                      @OA\Property(property="hasScreenshot", type="boolean", example=false),
 *                      @OA\Property(property="title", type="string", example="")
 *                  )
 *              )
 *          )
 *      ),
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
 *     )
 * )
 */
class BlogStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (auth()->user()->can('store', Blog::class)) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],

            'contents' => ['required', 'array', 'min:1'],
            'contents.*' => ['required', 'array'],
            'contents.*.locale' => ['required', Rule::in(Locale::allLocale())],
            'contents.*.title' => ['required', 'string', 'max:255'],
            'contents.*.content' => ['required', 'string'],
            'contents.*.slug' => ['required', 'string', 'max:255'],

            // props as part of each content item
            'contents.*.props' => ['required', 'array'],
            'contents.*.props.meta_keyword' => ['required', 'string', 'max:255'],
            'contents.*.props.meta_title' => ['required', 'string', 'max:255'],
            'contents.*.props.meta_description' => ['required', 'string'],
            'contents.*.props.canonical' => ['string', 'max:255'],
            'contents.*.props.no_index' => ['required', 'boolean'],
            'contents.*.props.short_desc' => ['required', 'string', 'max:255'],

            'props' => ['nullable'],
            'props.bento_layouts' => ['nullable', 'array'],

            'image' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:500',
            ],

            'status' => ['required', 'string', Rule::in(BlogStatus::statuses())],
            'published_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'related_blog_ids' => ['nullable', 'array'],
            'related_blog_ids.*' => ['integer', 'exists:blogs,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'contents.*.title' => 'عنوان',
            'contents.*.content' => 'محتوای',
            'contents.*.props.meta_keyword' => 'کلمات کلیدی متا',
            'contents.*.props.meta_title' => 'عنوان متا',
            'contents.*.props.meta_description' => 'توضیحات متا',
            'contents.*.props.canonical' => 'لینک کنونیکال',
        ];
    }

    protected function prepareForValidation(): void
    {
        $contents = $this->input('contents');
        $contents = is_array($contents) ? $contents : ($contents ? json_decode($contents, true) : null);

        if(auth()->user()->can('bentoLayoutUpdate', Blog::class)) {
            $props = $this->input('props');
            $props = is_array($props) ? $props : ($props ? json_decode($props, true) : null);
        } else {
            $props = [];
        }

        $relatedBlogIds = is_string($this->input('related_blog_ids'))
            ? explode(",", $this->input('related_blog_ids'))
            : $this->input('related_blog_ids');

        $this->merge([
            'contents' => $contents,
            'props' => $props,
            'related_blog_ids' => $relatedBlogIds
        ]);
    }

}
