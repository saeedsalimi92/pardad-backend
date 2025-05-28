<?php

namespace App\Providers;

use App\Models\BlogContent;
use App\Observers\BlogContentObserver;
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
    }
}
