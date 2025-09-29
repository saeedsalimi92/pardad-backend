<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->header('Accept-Language', 'fa'); // پیش‌فرض انگلیسی
        App::setLocale($locale);
        return $next($request);
    }
}
