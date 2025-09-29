<?php

namespace App\Observers;

use App\Models\ProductContent;

class ProductContentObserver
{
    /**
     * Handle the ProductContent "created" event.
     */
    public function created(ProductContent $productContent): void
    {
        $canonical = $productContent->getUrl();
        $uri = $productContent->getUrl();
        $productContent->props = array_merge($productContent->props, [
            "canonical" => $canonical,
            "url" => $uri,
        ]);
        $productContent->save();
    }

    /**
     * Handle the ProductContent "updated" event.
     */
    public function updated(ProductContent $productContent): void
    {
        //
    }

    /**
     * Handle the ProductContent "deleted" event.
     */
    public function deleted(ProductContent $productContent): void
    {
        //
    }

    /**
     * Handle the ProductContent "restored" event.
     */
    public function restored(ProductContent $productContent): void
    {
        //
    }

    /**
     * Handle the ProductContent "force deleted" event.
     */
    public function forceDeleted(ProductContent $productContent): void
    {
        //
    }
}
