<?php

namespace App\Observers;

use App\Models\BlogContent;

class BlogContentObserver
{
    /**
     * Handle the BlogContent "created" event.
     */
    public function created(BlogContent $blogContent): void
    {
        $canonical = $blogContent->getUrl();
        $uri = $blogContent->getUrl();
        $blogContent->props = array_merge($blogContent->props, [
            "canonical" => $canonical,
            "url" => $uri,
        ]);
        $blogContent->save();
    }

    /**
     * Handle the BlogContent "updated" event.
     */
    public function updated(BlogContent $blogContent): void
    {
        //
    }

    /**
     * Handle the BlogContent "deleted" event.
     */
    public function deleted(BlogContent $blogContent): void
    {
        //
    }

    /**
     * Handle the BlogContent "restored" event.
     */
    public function restored(BlogContent $blogContent): void
    {
        //
    }

    /**
     * Handle the BlogContent "force deleted" event.
     */
    public function forceDeleted(BlogContent $blogContent): void
    {
        //
    }
}
