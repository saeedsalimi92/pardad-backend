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

    Route::group(['prefix' => 'blog-faqs'], function () {
        Route::get('/', [BlogFaqController::class, 'index']);
        Route::post('/', [BlogFaqController::class, 'store']);
        Route::get('{id}/', [BlogFaqController::class, 'show']);
        Route::put('{id}/', [BlogFaqController::class, 'update']);
        Route::delete('{id}/', [BlogFaqController::class, 'destroy']);
    });

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
    });

    Route::get('/brand', [BrandController::class, 'index']);
    Route::get('/brand/{id}', [BrandController::class, 'show']);
    Route::post('/brand', [BrandController::class, 'store']);
    Route::post('/brand/{id}', [BrandController::class, 'update']);
    Route::delete('/brand/{id}', [BrandController::class, 'destroy']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::get('/list/category', [CategoryController::class, 'list']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::post('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
    Route::post('/category/{id}/images',[CategoryController::class, 'updateImages']);
    Route::delete('/category/{category}/images/{id}',[CategoryController::class, 'deleteImage']);
    Route::post('/category/{category}/logos',[CategoryController::class, 'updateLogos']);

    Route::group(['prefix' => 'sub-category'], function () {
        Route::get('/', [SubCategoryController::class, 'index']);
        Route::get('{id}/', [SubCategoryController::class, 'show']);
        Route::get('list/{parent_id}/', [SubCategoryController::class, 'indexByParent']);
        Route::post('/', [SubCategoryController::class, 'store']);
        Route::post('{id}/', [SubCategoryController::class, 'update']);
        Route::delete('{id}/', [SubCategoryController::class, 'destroy']);
        Route::post('{id}/images/',[SubCategoryController::class, 'updateImages']);
        Route::delete('{sub-category}/images/{id}/',[SubCategoryController::class, 'deleteImage']);
        Route::post('{sub-category}/logos/',[SubCategoryController::class, 'updateLogos']);
    });

    Route::get('/list/sub-category', [SubCategoryController::class, 'list']);
    Route::get('/list', [CategoryController::class, 'allList']);
    Route::get('/list/sub-category/{parent_id}', [SubCategoryController::class, 'listByParent']);
});
