<?php

use App\Http\Controllers\Admin\V1\AuthController;
use App\Http\Controllers\Admin\V1\BlogController;
use App\Http\Controllers\Admin\V1\BrandController;
use App\Http\Controllers\Admin\V1\CategoryController;
use App\Http\Controllers\Admin\V1\CommentController;
use App\Http\Controllers\Admin\V1\MenuController;
use App\Http\Controllers\Admin\V1\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register/', [AuthController::class, 'register']);
Route::post('login/', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/menu', [MenuController::class, 'getMenu']);

    Route::post('/upload-image', [UploadController::class , 'uploadImage']);
    Route::post('/upload-file', [UploadController::class , 'uploadFile']);
    Route::post('/upload-media', [UploadController::class , 'uploadMedia']);

    Route::group(['prefix' => 'comments'], function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::get('{id}/', [CommentController::class, 'show']);
        Route::post('{id}/reply', [CommentController::class, 'reply']);
        Route::post('{id}/status/change', [CommentController::class, 'changeStatus']);
    });

    Route::apiResource('blog', BlogController::class)->except(['update']);
    Route::post('blog/{blog}/update', [BlogController::class, 'update']);
    Route::group(['prefix' => 'blog'], function () {
        Route::post('{blog}/status/change',[BlogController::class, 'changeStatus']);

        Route::group(['prefix' => '{blog}/faq'], function () {
            Route::get("/", [BlogController::class, 'faqIndex']);
            Route::post("/", [BlogController::class, 'faqStore']);
            Route::post("{faq}/update", [BlogController::class, 'faqUpdate']);
            Route::delete("{faq}/", [BlogController::class, 'faqDestroy']);
        });
    });

    Route::apiResource('categories', CategoryController::class)->except(['update']);
    Route::post('categories/{category}/update', [CategoryController::class, 'update']);

    Route::get('/brand', [BrandController::class, 'index']);
    Route::get('/brand/{id}', [BrandController::class, 'show']);
    Route::post('/brand', [BrandController::class, 'store']);
    Route::post('/brand/{id}', [BrandController::class, 'update']);
    Route::delete('/brand/{id}', [BrandController::class, 'destroy']);

    Route::get('/list', [CategoryController::class, 'allList']);
});
