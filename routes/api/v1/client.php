<?php

use App\Http\Controllers\V1\Client\BlogController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'client.'], function () {

    Route::apiResource('blog', BlogController::class);
    Route::group(['prefix' => 'blog'], function () {
        Route::get('list/', [BlogController::class, 'list']);
        Route::get('list/{parent_id}/', [BlogController::class, 'listByParent']);
    });

//    Route::group(['prefix' => 'brand'], function () {
//        Route::get('/', [BrandController::class, 'client_Index']);
//        Route::get('{id}/', [BrandController::class, 'client_show']);
//    });
//
//    Route::group(['prefix' => 'categories'], function () {
//        Route::get('/', [CategoryController::class, 'client_index']);
//        Route::get('parent/', [CategoryController::class, 'client_index_parent']);
//        Route::get('{id}/', [CategoryController::class, 'client_show']);
//        Route::get('list/', [CategoryController::class, 'client_list']);
//
//        Route::group(['prefix' => 'sub-category'], function () {
//            Route::get('/', [SubCategoryController::class, 'client_index']);
//            Route::get('{id}/', [SubCategoryController::class, 'client_show']);
//            Route::get('list/', [SubCategoryController::class, 'client_list']);
//            Route::get('list/{parent_id}/', [SubCategoryController::class, 'client_listByParent']);
//        });
//    });
//
//    Route::group(['prefix' => 'comments'], function () {
//        Route::post('/', [CommentController::class, 'store']);
//        Route::post('reply/{id}/', [CommentController::class, 'reply']);
//    });
});
