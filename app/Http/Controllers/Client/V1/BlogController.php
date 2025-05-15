<?php

namespace App\Http\Controllers\Client\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Blog\BlogIndexRequest;
use App\Http\Requests\Admin\V1\Blog\BlogListRequest;
use App\Http\Requests\Admin\V1\Blog\BlogShowRequest;
use App\Http\Requests\Client\V1\Blog\BlogListByParentRequest;
use App\Models\Blog;
use App\Services\BlogService;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService)
    {
    }

    /**
     * @OA\Get(
     *     path="/admin/blogs",
     *     summary="لیست بلاگ‌ها",
     *     tags={"Admin - Blogs"},
     *     @OA\Response(
     *         response=200,
     *         description="لیست با موفقیت برگشت داده شد"
     *     )
     * )
     */
    public function index(BlogIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data["status"] = Blog::STATUS_ACTIVE;
        $blogs = $this->blogService->index($data);

        return $this->apiResponse(data: $blogs->toArray(), paginationData: generatePaginateData($blogs));
    }

    public function listByParent(BlogListByParentRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data["status"] = Blog::STATUS_ACTIVE;
        $blogs = $this->blogService->getList($data);

        return $this->apiResponse(data: $blogs);
    }

    public function list(BlogListRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data["status"] = Blog::STATUS_ACTIVE;
        $blogs = $this->blogService->getList($data);

        return $this->apiResponse(data: $blogs);
    }

    public function indexByParent(\App\Http\Requests\Admin\V1\Blog\BlogListByParentRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data["status"] = Blog::STATUS_ACTIVE;
        $blogs = $this->blogService->index($data);

        return $this->apiResponse(data: $blogs->toArray(), paginationData: generatePaginateData($blogs));
    }

    public function show(BlogShowRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        if($blog->status !== Blog::STATUS_ACTIVE) {
            return $this->apiResponse(success: false, message: __("response.blog.not_found"));
        }

        $blog->loadMissing(['comments.replies', 'faqs']);

        return $this->apiResponse(data: $blog);
    }
}
