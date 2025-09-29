<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

/**
 * @property $title
 */
trait HasSlug
{
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::slug($value, "-", null),
        );
    }
}
