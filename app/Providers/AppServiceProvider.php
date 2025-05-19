<?php

namespace App\Providers;

use App\Models\Category;
use App\Repositories\BlogRepositoryInterface;
use App\Repositories\BrandRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\Eloquent\BlogRepository;
use App\Repositories\Eloquent\BrandRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\ImageRepository;
use App\Repositories\Eloquent\SubCategoryRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\SubCategoryRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->bindRepositories();
        $this->bindServices();
        $this->loadMigrationDirectories();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }

    public function bindRepositories(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
    }

    public function bindServices(): void
    {
        //
    }

    private function loadMigrationDirectories(): void
    {
        $paths = $this->getAllSubdirectories("database/migrations");
        $this->loadMigrationsFrom($paths);
    }

    private function getAllSubdirectories($dir): array
    {
        $subdirectories = [];

        if(!is_dir($dir)) {
            return $subdirectories;
        }

        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..') {
                $path = $dir . DIRECTORY_SEPARATOR . $item;
                if (is_dir($path)) {
                    $subdirectories[] = $path;
                    $subdirectoriesToAdd = $this->getAllSubdirectories($path);
                    foreach ($subdirectoriesToAdd as $subDirToAdd) {
                        $subdirectories[] = $subDirToAdd;
                    }
                }
            }
        }

        return $subdirectories;
    }
}
