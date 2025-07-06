<?php

use App\Http\Controllers\Admin\V1\AuthController;
use App\Http\Controllers\Admin\V1\BentoLayoutController;
use App\Http\Controllers\Admin\V1\BlogController;
use App\Http\Controllers\Admin\V1\BrandController;
use App\Http\Controllers\Admin\V1\CategoryController;
use App\Http\Controllers\Admin\V1\CommentController;
use App\Http\Controllers\Admin\V1\MenuController;
use App\Http\Controllers\Admin\V1\PermissionController;
use App\Http\Controllers\Admin\V1\RoleController;
use App\Http\Controllers\Admin\V1\UploadController;
use App\Http\Controllers\Admin\V1\UserController;
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


    Route::group(["prefix" => "roles"], function () {
        Route::get("/", [RoleController::class, "index"]);
        Route::post("/", [RoleController::class, "store"]);
        Route::post("/assign/users", [RoleController::class, "assignUser"]);
        Route::post("/assign/permissions", [RoleController::class, "givePermissionToRole"]);
    });

    Route::group(["prefix" => "permissions"], function () {
        Route::get("/", [PermissionController::class, "index"]);
    });

    Route::group(["prefix" => "users"], function () {
        Route::get("/", [UserController::class, "index"]);
        Route::get("permissions/", [UserController::class, "permissions"]);
    });



    Route::group(['prefix' => 'comments'], function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::get('{id}/', [CommentController::class, 'show']);
        Route::post('{id}/reply', [CommentController::class, 'reply']);
        Route::post('{id}/status/change', [CommentController::class, 'changeStatus']);
    });

    Route::group(['prefix' => 'blog'], function () {
        Route::get("list", [BlogController::class, 'list']);
        Route::post('{blog}/update', [BlogController::class, 'update']);

        Route::post('{blog}/status/change',[BlogController::class, 'changeStatus']);
        Route::group(['prefix' => '{blog}/faq'], function () {
            Route::get("/", [BlogController::class, 'faqIndex']);
            Route::post("/", [BlogController::class, 'faqStore']);
            Route::get("/{faq}/", [BlogController::class, 'faqShow']);
            Route::post("{faq}/update", [BlogController::class, 'faqUpdate']);
            Route::delete("{faq}/", [BlogController::class, 'faqDestroy']);
        });
    });
    Route::apiResource('blog', BlogController::class)->except(['update']);


    Route::group(["prefix" => "categories"], function () {
        Route::post('{category}/update', [CategoryController::class, 'update']);
        Route::get('list', [CategoryController::class, 'getList']);
    });
    Route::apiResource('categories', CategoryController::class)->except(['update']);

    Route::group(["prefix" => "brands"], function () {
        Route::post('{brand}/update', [BrandController::class, 'update']);
        Route::get('list', [BrandController::class, 'getList']);
    });
    Route::apiResource('brands', BrandController::class)->except(['update']);


    Route::apiResource("bento-layouts", BentoLayoutController::class);



    Route::get('/list', [CategoryController::class, 'allList']);
});
