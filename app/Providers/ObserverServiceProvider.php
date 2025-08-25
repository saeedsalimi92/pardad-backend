<?php

namespace App\Providers;

use App\Models\BlogContent;
use App\Models\CategoryContent;
use App\Models\ProductContent;
use App\Observers\BlogContentObserver;
use App\Observers\CategoryContentObserver;
use App\Observers\ProductContentObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        BlogContent::observe(BlogContentObserver::class);
        CategoryContent::observe(CategoryContentObserver::class);
        ProductContent::observe(ProductContentObserver::class);
    }
}
