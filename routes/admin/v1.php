<?php

use App\Http\Controllers\Admin\V1\AuthController;
use App\Http\Controllers\Admin\V1\BlogController;
use App\Http\Controllers\Admin\V1\BlogFaqController;
use App\Http\Controllers\Admin\V1\BrandController;
use App\Http\Controllers\Admin\V1\CategoryController;
use App\Http\Controllers\Admin\V1\CommentController;
use App\Http\Controllers\Admin\V1\MenuController;
use App\Http\Controllers\Admin\V1\SubCategoryController;
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
        Route::get('/', [CommentController::class, 'getAllComments']);
        Route::post('/', [CommentController::class, 'store']);
        Route::post('reply/{id}/', [CommentController::class, 'reply']);
        Route::get('replies/{id}/', [CommentController::class, 'showReplies']);
        Route::patch('status/{id}/', [CommentController::class, 'updateStatus']);
        Route::delete('{id}/', [CommentController::class, 'destroy']);
    });

    Route::apiResource('blog', BlogController::class);
    Route::group(['prefix' => 'blog'], function () {
        Route::patch('{blog}/status/change',[BlogController::class, 'changeStatus']);

        Route::group(['prefix' => '{blog}/faq'], function () {
            Route::get("/", [BlogController::class, 'faqIndex']);
            Route::post("/", [BlogController::class, 'faqStore']);
            Route::put("{faq}/", [BlogController::class, 'faqUpdate']);
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
