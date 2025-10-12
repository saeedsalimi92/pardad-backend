<?php

use Illuminate\Support\Facades\Schedule;



Schedule::command('sitemap:blog')
    ->dailyAt('02:00')
    ->appendOutputTo(storage_path('logs/sitemap.log'));
