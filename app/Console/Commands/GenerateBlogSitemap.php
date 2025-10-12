<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Blog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GenerateBlogSitemap extends Command
{
    protected $signature = 'sitemap:blog';
    protected $description = 'Generate sitemap for all blog posts';

    public function handle()
    {
        Log::info('Starting sitemap generation...');

        // از ENV قابل تنظیم؛ درصورت نبود، روی پروڈاکشن ست می‌شود
        $baseUrl = rtrim(env('FRONTEND_BASE_URL', 'https://pardad-group.com'), '/');
        $now = now()->toAtomString();

        $blogs = Blog::with([
            // نوع کتگوری را هم ایگرلود می‌کنیم تا بتوانیم مسیر را تعیین کنیم
            'category:id,type',
            'category.contents' => function ($query) {
                $query->select('category_id', 'slug');
            },
            'contents' => function ($query) {
                $query->select('blog_id', 'slug');
            },
        ])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        // استاندارد: http (نه https) برای xmlns
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // صفحات استاتیک
        $staticPaths = ['/', '/contact', '/about', '/category', '/industrial-brands', '/insight-guides'];
        foreach ($staticPaths as $path) {
            $url = $baseUrl . $path; // $baseUrl بدون اسلش پایانی است
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url}</loc>\n";
            $xml .= "    <lastmod>{$now}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>1.0</priority>\n";
            $xml .= "  </url>\n";
        }

        // پست‌ها
        foreach ($blogs as $blog) {
            // نوع کتگوری
            $categoryType = $blog->category?->type;

            // اگر expert → مسیر بخش = technical-knowledge، در غیر این صورت blog
            $section = $categoryType === 'expert' ? 'technical-knowledge' : 'blog';

            // اسلاگ کتگوری و پست
            $categorySlug = $blog->category?->contents->first()?->slug ?? 'uncategorized';
            $blogSlug     = $blog->contents->first()?->slug ?? Str::slug((string)($blog->title ?? $blog->code));

            $url = "{$baseUrl}/{$section}/{$categorySlug}/{$blog->code}/{$blogSlug}";
            $lastMod = $blog->updated_at?->toAtomString() ?? $now;

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url}</loc>\n";
            $xml .= "    <lastmod>{$lastMod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        File::put(public_path('sitemap.xml'), $xml);

        $message = '✅ sitemap.xml generated successfully! Count: ' . count($blogs);
        Log::info($message);
        $this->info($message);
    }
}

