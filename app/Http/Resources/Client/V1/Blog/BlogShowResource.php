<?php

namespace App\Http\Resources\Client\V1\Blog;

use App\Http\Resources\Client\V1\User\UserShowResource;
use App\Http\Resources\Client\V1\Category\CategoryContentShowResource;
use App\Http\Resources\Client\V1\Category\CategoryShowResource;
use App\Http\Resources\Client\V1\Faq\FaqIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // ⬅️ اضافه شد

/**
 * @OA\Schema(
 *     schema="ApiV1BlogShowResource",
 *     title="Blog Show Resource",
 *     description="Detailed blog resource for client",
 *     type="object",
 *     @OA\Property(property="id", type="string", example="B123"),
 *     @OA\Property(property="category", type="object"),
 *     @OA\Property(property="image", type="string", format="url", example="https://example.com/image.jpg"),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1BlogContentShowResource")
 *     ),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="status_translate", type="string", example="Published"),
 *     @OA\Property(
 *         property="related_blogs",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1BlogIndexResource")
 *     ),
 *     @OA\Property(
 *         property="faq",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ApiV1FaqIndexResource")
 *     ),
 *     @OA\Property(property="props", type="object"),
 *     @OA\Property(property="published_at", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="author", ref="#/components/schemas/UserShowResource"),
 *     @OA\Property(property="valid_url", type="string")
 * )
 */
class BlogShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $contents = BlogContentShowResource::collection($this->contents);
        $category = CategoryContentShowResource::make($this->category?->contents?->first());

        // تعیین سکشن بر اساس نوع کتگوری
        $section = ($this->category?->type === 'expert') ? 'technical-knowledge' : 'blog';

        // بررسی canonical (ممکن است absolute یا relative باشد)
        $canonical = $this->contents?->first()->props['canonical'] ?? null;
        $canonical = is_string($canonical) ? trim($canonical) : null;

        if ($canonical) {
            if (Str::startsWith($canonical, ['http://', 'https://'])) {
                // canonical مطلق است، همان را برگردان
                $valid_url = $canonical;
            } else {
                // canonical نسبی است؛ با سکشن صحیح prefix کن
                $canonical = ltrim($canonical, '/');
                // اگر کاربر خودش سکشن را گذاشته بود، همان را نگه دار
                if (!Str::startsWith($canonical, [$section . '/', 'blog/', 'technical-knowledge/'])) {
                    $valid_url = '/' . $section . '/' . $canonical;
                } else {
                    $valid_url = '/' . $canonical;
                }
            }
        } else {
            // ساخت مسیر پیش‌فرض
            $categorySlug = $this->category?->contents?->first()->slug ?? null;
            $contentSlug  = $this->contents?->first()->slug ?? null;
            $blogCode     = $this->code;

            $valid_url = ($categorySlug && $contentSlug && $blogCode)
                ? '/' . $section . '/' . $categorySlug . '/' . $blogCode . '/' . $contentSlug
                : null;
        }

        return [
            "id"               => $this->code,
            "category"         => $category,
            "image"            => url($this?->featuredImage?->path),
            "contents"         => $contents,
            "status"           => $this->status,
            "status_translate" => $this->status_translate,
            "related_blogs"    => BlogIndexResource::collection($this->relatedBlogs),
            "faq"              => FaqIndexResource::collection($this->faqs),
            "props"            => $this->props,
            "published_at"     => $this->published_at,
            "created_at"       => $this->created_at,
            "updated_at"       => $this->updated_at,
            "author"           => UserShowResource::make($this->author),
            "valid_url"        => $valid_url,
        ];
    }
}
