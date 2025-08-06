<?php

use App\Http\Controllers\Client\V1\BlogController;
use App\Http\Controllers\Client\V1\BrandController;
use App\Http\Controllers\Client\V1\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

Route::group(['as' => 'client.'], function () {

    Route::apiResource('blog', BlogController::class);
    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::get('{code}/comments', [BlogController::class, 'comments']);
        Route::post('{code}/comments', [BlogController::class, 'commentStore']);
        Route::get('{code}/{slug?}', [BlogController::class, 'show']);
    });

    Route::group(['prefix' => 'categories'], function () {
       Route::get("/", [CategoryController::class, 'index']);
       Route::get("{code}/{slug?}", [CategoryController::class, 'show']);
    });
    Route::group(['prefix' => 'brands'], function () {
        Route::get("/", [BrandController::class, 'index']);
        Route::get("{code}", [BrandController::class, 'show']);
    });
    Route::get('/sitemap-urls', function () {
        // کش کردن URL‌ها برای 12 ساعت
        return Cache::remember('sitemap_urls', 43200, function () {
            $baseUrl = 'https://pardad-group.com';
            $now = Carbon::now()->toIso8601String(); // زمان فعلی برای <lastmod>

            // URLهای استاتیک
            $staticUrls = [
                '/', '/contact', '/about', '/category', '/industrial-brands', '/insight-guides'
            ];

            $urls = collect();

            // افزودن URLهای استاتیک
            foreach ($staticUrls as $path) {
                $urls->push([
                    'loc' => rtrim($baseUrl, '/') . $path,
                    'lastmod' => $now,
                    'changefreq' => 'monthly',
                    'priority' => 1.0,
                ]);
            }

            // افزودن URLهای وبلاگ‌ها
            $blogs = Blog::with([
                'category.contents' => function ($query) {
                    $query->select('category_id', 'slug');
                },
                'contents' => function ($query) {
                    $query->select('blog_id', 'slug');
                }
            ])
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->get();

            foreach ($blogs as $blog) {
                $categoryTitle = $blog->category?->contents->first()?->slug ?? 'uncategorized';
                $blogSlug = $blog->contents->first()?->slug;
                $url = "{$baseUrl}/blog/{$categoryTitle}/{$blog->code}/{$blogSlug}";

                $lastMod = $blog->updated_at?->toAtomString() ?? $now;

                $urls->push([
                    'loc' => $url,
                    'lastmod' => $lastMod,
                    'changefreq' => 'weekly',
                    'priority' => 0.8,
                ]);
            }

            // بازگرداندن نتیجه به صورت آرایه JSON
            return response()->json($urls);
        });
    });

});
