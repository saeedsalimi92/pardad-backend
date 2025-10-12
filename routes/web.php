<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('api/documentation/admin/oauth2-callback', function () {
    abort(404);
})->name('l5-swagger.admin.oauth2_callback');
