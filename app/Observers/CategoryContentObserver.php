<?php

namespace App\Observers;


use App\Models\CategoryContent;

class CategoryContentObserver
{
    /**
     * Handle the CategoryContent "created" event.
     */
    public function created(CategoryContent $categoryContent): void
    {
        $canonical = $categoryContent->getUrl();
        $uri = $categoryContent->getUrl();
        $categoryContent->props = array_merge($categoryContent->props, [
            "canonical" => $canonical,
            "url" => $uri,
        ]);
        $categoryContent->save();
    }

    /**
     * Handle the CategoryContent "updated" event.
     */
    public function updated(CategoryContent $blogContent): void
    {
        //
    }

    /**
     * Handle the CategoryContent "deleted" event.
     */
    public function deleted(CategoryContent $blogContent): void
    {
        //
    }

    /**
     * Handle the CategoryContent "restored" event.
     */
    public function restored(CategoryContent $blogContent): void
    {
        //
    }

    /**
     * Handle the CategoryContent "force deleted" event.
     */
    public function forceDeleted(CategoryContent $blogContent): void
    {
        //
    }
}
