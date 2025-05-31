<?php

use App\Http\Controllers\Client\V1\BlogController;
use App\Http\Controllers\Client\V1\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'client.'], function () {

    Route::apiResource('blog', BlogController::class);
    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::get('{code}/comments', [BlogController::class, 'comments']);
        Route::post('{code}/comments', [BlogController::class, 'commentStore']);
        Route::get('{code}/{slug?}', [BlogController::class, 'show']);
    });

    Route::group(['prefix' => 'categories'], function () {
       Route::get("/", [CategoryController::class, 'index']);
       Route::get("{code}/{slug?}", [CategoryController::class, 'show']);
    });

});
