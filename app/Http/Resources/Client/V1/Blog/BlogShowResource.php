<?php

namespace App\Http\Resources\Client\V1\Blog;

use App\Http\Resources\Client\V1\User\UserShowResource;
use App\Http\Resources\Client\V1\Category\CategoryContentShowResource;
use App\Http\Resources\Client\V1\Category\CategoryShowResource;
use App\Http\Resources\Client\V1\Faq\FaqIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class BlogShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $contents = BlogContentShowResource::collection($this->contents);
        $category = CategoryContentShowResource::make($this->category?->contents?->first());

        // بررسی وجود canonical
        $canonical = $this->contents?->first()->props['canonical'] ?? null;

        if ($canonical) {
            $valid_url = $canonical;
        } else {
            // استخراج مقادیر مورد نیاز برای ساخت URL
            $categorySlug = $this->category?->contents?->first()->slug ?? null;
            $contentSlug = $this->contents?->first()->slug ?? null;
            $blogCode = $this->code;

            // اگر هر یک از مقادیر ضروری وجود نداشت، null برگردان
            if ($categorySlug === null || $contentSlug === null || $blogCode === null) {
                $valid_url = null;
            } else {
                $valid_url = "/blog/{$categorySlug}/{$blogCode}/{$contentSlug}";
            }
        }

        return [
            "id" => $this->code,
            "category" => $category,
            "image" => url($this?->featuredImage?->path),
            "contents" => $contents,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "related_blogs" => BlogIndexResource::collection($this->relatedBlogs),
            "faq" => FaqIndexResource::collection($this->faqs),
            "props" => $this->props,
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "author" => UserShowResource::make($this->author),
            "valid_url" => $valid_url,
        ];
    }
}
