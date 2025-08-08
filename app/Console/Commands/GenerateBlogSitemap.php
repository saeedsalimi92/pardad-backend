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
        $baseUrl =  'https://pardad-group.com';
        $now = now()->toAtomString(); // زمان فعلی برای <lastmod>

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

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $staticUrls = ['/', '/contact', '/about' , '/category', '/industrial-brands' , '/insight-guides'];

        foreach ($staticUrls as $path) {
            $url = rtrim($baseUrl, '/') . $path;
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url}</loc>\n";
            $xml .= "    <lastmod>{$now}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>1.0</priority>\n";
            $xml .= "  </url>\n";
        }



        foreach ($blogs as $blog) {
            $categoryTitle = $blog->category?->contents->first()?->slug ?? 'uncategorized';
            $categorySlug = $categoryTitle;

            $blogSlug = $blog->contents->first()?->slug;
            $url = "{$baseUrl}/blog/{$categoryTitle}/{$blog->code}/{$blogSlug}";

            $lastMod = $blog->updated_at?->toAtomString() ?? $now;

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url}</loc>\n";
            $xml .= "    <lastmod>{$lastMod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        // ذخیره در public/blog-sitemap.xml
        File::put(public_path('sitemap.xml'), $xml);
        $message = '✅ sitemap.xml generated successfully! Count: ' . count($blogs);
        Log::info($message);

        $this->info($message);
    }
}
