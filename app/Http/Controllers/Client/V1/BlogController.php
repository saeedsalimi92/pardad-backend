<?php

namespace App\Http\Controllers\Client\V1;


use App\Enums\BlogStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\V1\Blog\BlogCommentIndexRequest;
use App\Http\Requests\Client\V1\Blog\BlogCommentStoreRequest;
use App\Http\Requests\Client\V1\Blog\BlogIndexRequest;
use App\Http\Requests\Client\V1\Blog\BlogShowRequest;
use App\Http\Resources\Admin\V1\Blog\BlogIndexResource;
use App\Http\Resources\Client\V1\Blog\BlogShowResource;
use App\Http\Resources\PaginationResource;
use App\Services\BlogService;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService)
    {
    }

    public function index(BlogIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $blogs = $this->blogService->getPublishedBlogsPaginated($data);
        $resource = BlogIndexResource::make($blogs);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($blogs));
    }

    public function show($id, BlogShowRequest $request): \Illuminate\Http\JsonResponse
    {
        $blog = $this->blogService->findById($id);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        if($blog->status !== BlogStatus::Published) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        if(Carbon::parse($blog->published_at)->isFuture()) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(data: $resource);
    }

    public function comments(BlogCommentIndexRequest $request): \Illuminate\Http\JsonResponse
    {

    }

    public function commentStore(BlogCommentStoreRequest $request): \Illuminate\Http\JsonResponse
    {

    }

}
