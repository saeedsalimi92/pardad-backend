<?php

namespace App\Http\Controllers\V1\Client;


use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Blog\BlogIndexRequest;
use App\Http\Requests\V1\Admin\Blog\BlogListRequest;
use App\Http\Requests\V1\Admin\Blog\BlogShowRequest;
use App\Http\Requests\V1\Client\Blog\BlogListByParentRequest;
use App\Models\Blog;
use App\Services\BlogService;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService)
    {
    }

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

    public function indexByParent(\App\Http\Requests\V1\Admin\Blog\BlogListByParentRequest $request): \Illuminate\Http\JsonResponse
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
