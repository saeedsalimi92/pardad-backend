<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;











Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/menu', [MenuController::class, 'getMenu']);

    Route::post('/upload-image', [UploadController::class , 'uploadImage']);
    Route::post('/upload-file', [UploadController::class , 'uploadFile']);
    Route::post('/upload-media', [UploadController::class , 'uploadMedia']);





    Route::prefix('api/v1/comments')->group(function () {
        Route::get('/', [CommentController::class, 'index']); // سایت
        Route::get('/all', [CommentController::class, 'getAllComments']); // پنل ادمین با فیلتر
        Route::post('/', [CommentController::class, 'store']); // ارسال کامنت
        Route::post('/reply/{id}', [CommentController::class, 'reply']); // پاسخ به کامنت
        Route::get('/replies/{id}', [CommentController::class, 'showReplies']); // گرفتن ریپلای‌ها
        Route::patch('/status/{id}', [CommentController::class, 'updateStatus']); // تغییر وضعیت
        Route::delete('/{id}', [CommentController::class, 'destroy']);
    });

    Route::prefix('v1')->group(function () {
        Route::get('blog-faqs', [BlogFaqController::class, 'index']);
        Route::get('blog-faqs/{id}', [BlogFaqController::class, 'show']);
        Route::post('blog-faqs', [BlogFaqController::class, 'store']);
        Route::put('blog-faqs/{id}', [BlogFaqController::class, 'update']);
        Route::delete('blog-faqs/{id}', [BlogFaqController::class, 'destroy']);
    });

    Route::get('/blog', [BlogController::class, 'index']);
    Route::get('/blog/{id}', [BlogController::class, 'show']);
    Route::post('/blog', [BlogController::class, 'store']);
    Route::get('/list/blog', [BlogController::class, 'list']);
    Route::get('/list/blog/{parent_id}', [BlogController::class, 'listByParent']);
    Route::get('/blog/list/{parent_id}', [BlogController::class, 'indexByParent']);
    Route::post('/blog/{id}', [BlogController::class, 'update']);
    Route::delete('/blog/{id}', [BlogController::class, 'destroy']);
    Route::post('/blog/{id}/images',[BlogController::class, 'updateImages']);
    Route::delete('/blog/{blog}/images/{id}',[BlogController::class, 'deleteImage']);
    Route::post('/blog/{blog}/logos',[BlogController::class, 'updateLogos']);

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

    Route::get('/sub-category', [SubCategoryController::class, 'index']);
    Route::get('/sub-category/{id}', [SubCategoryController::class, 'show']);
    Route::get('/list/sub-category', [SubCategoryController::class, 'list']);
    Route::get('/list', [CategoryController::class, 'allList']);
    Route::get('/list/sub-category/{parent_id}', [SubCategoryController::class, 'listByParent']);
    Route::get('/sub-category/list/{parent_id}', [SubCategoryController::class, 'indexByParent']);
    Route::post('/sub-category', [SubCategoryController::class, 'store']);
    Route::post('/sub-category/{id}', [SubCategoryController::class, 'update']);
    Route::delete('/sub-category/{id}', [SubCategoryController::class, 'destroy']);
    Route::post('/sub-category/{id}/images',[SubCategoryController::class, 'updateImages']);
    Route::delete('/sub-category/{sub-category}/images/{id}',[SubCategoryController::class, 'deleteImage']);
    Route::post('/sub-category/{sub-category}/logos',[SubCategoryController::class, 'updateLogos']);

    Route::get('/about', [AboutController::class, 'index']);
    Route::get('/about/{id}', [AboutController::class, 'show']);
    Route::post('/about', [AboutController::class, 'store']);
    Route::post('/about/{id}', [AboutController::class, 'update']);
    Route::delete('/about/{id}', [AboutController::class, 'destroy']);
    Route::post('/about/{id}/images',[AboutController::class, 'updateImages']);
    Route::delete('/about/{about}/images/{id}',[AboutController::class, 'deleteImage']);


    Route::get('/contact', [ContactController::class, 'index']);
    Route::get('/contact/{id}', [ContactController::class, 'show']);
    Route::post('/contact', [ContactController::class, 'store']);
    Route::post('/contact/{id}', [ContactController::class, 'update']);
    Route::delete('/contact/{id}', [ContactController::class, 'destroy']);
    Route::post('/contact/{id}/images',[ContactController::class, 'updateImages']);
    Route::delete('/contact/{contact}/images/{id}',[ContactController::class, 'deleteImage']);



    Route::get('/team', [TeamsController::class, 'index']);
    Route::get('/team/{id}', [TeamsController::class, 'show']);
    Route::post('/team', [TeamsController::class, 'store']);
    Route::post('/team/{id}', [TeamsController::class, 'update']);
    Route::delete('/team/{id}', [TeamsController::class, 'destroy']);
    Route::post('/team/{id}/images',[TeamsController::class, 'updateImages']);
    Route::delete('/team/{team}/images/{id}',[TeamsController::class, 'deleteImage']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tags/{id}', [TagController::class, 'show']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::post('/tags/{id}', [TagController::class, 'update']);
    Route::delete('/tags/{id}', [TagController::class, 'destroy']);


    Route::get('/logo', [LogoController::class, 'index']);
    Route::get('/storage', function () {

        Artisan::call('storage:link');
    });
    Route::get('/logo/{id}', [LogoController::class, 'show']);
    Route::post('/logo', [LogoController::class, 'store']);
    Route::delete('/logo/{id}', [LogoController::class, 'destroy']);
});
Route::prefix('v2')->group(function () {
    Route::get('/blog', [BlogController::class, 'client_Index']);
    Route::get('/blog/{id}', [BlogController::class, 'client_show']);
    Route::get('/list/blog', [BlogController::class, 'client_list']);
    Route::get('/list/blog/{parent_id}', [BlogController::class, 'client_listByParent']);
    Route::get('/blog/list/{parent_id}', [BlogController::class, 'client_indexByParent']);

    Route::get('/brand', [BrandController::class, 'client_Index']);
    Route::get('/brand/{id}', [BrandController::class, 'client_show']);

    Route::get('/category', [CategoryController::class, 'client_index']);
    Route::get('/categories', [CategoryController::class, 'client_index_parent']);
    Route::get('/category/{id}', [CategoryController::class, 'client_show']);
    Route::get('/list/category', [CategoryController::class, 'client_list']);


    Route::get('/sub-category', [SubCategoryController::class, 'client_index']);
    Route::get('/sub-category/{id}', [SubCategoryController::class, 'client_show']);
    Route::get('/list/sub-category', [SubCategoryController::class, 'client_list']);
    Route::get('/list/sub-category/{parent_id}', [SubCategoryController::class, 'client_listByParent']);
    Route::get('/sub-category/list/{parent_id}', [SubCategoryController::class, 'client_indexByParent']);

});





