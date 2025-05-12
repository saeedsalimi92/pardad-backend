<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLogoRequest;
use App\Http\Requests\V1\Admin\Blog\BlogChangeStatusRequest;
use App\Http\Requests\V1\Admin\Blog\BlogDestroyRequest;
use App\Http\Requests\V1\Admin\Blog\BlogIndexRequest;
use App\Http\Requests\V1\Admin\Blog\BlogListByParentRequest;
use App\Http\Requests\V1\Admin\Blog\BlogListRequest;
use App\Http\Requests\V1\Admin\Blog\BlogShowRequest;
use App\Http\Requests\V1\Admin\Blog\BlogStoreRequest;
use App\Http\Requests\V1\Admin\Blog\BlogUpdateRequest;
use App\Http\Resources\V1\Admin\Blog\BlogIndexResource;
use App\Http\Resources\V1\Admin\Blog\BlogListResource;
use App\Http\Resources\V1\Admin\Blog\BlogShowResource;
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
        $blogs = $this->blogService->index($data);

        $resource = BlogIndexResource::collection($blogs);

        return $this->apiResponse(data: $resource, paginationData: generatePaginateData($blogs));
    }

    public function list(BlogListRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->getList($data);

        $resource = BlogListResource::collection($blogs);

        return $this->apiResponse(data: $resource);
    }

    public function listByParent(BlogListByParentRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->getList($data);

        $resource = BlogListResource::collection($blogs);

        return $this->apiResponse(data: $resource);
    }

    public function indexByParent(BlogListByParentRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->index($data);

        $resource = BlogIndexResource::collection($blogs);

        return $this->apiResponse(data: $resource, paginationData: generatePaginateData($blogs));
    }

    public function store(BlogStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $blog = $this->blogService->create($data);

        $blog->storeFeaturedImage($request->file('image'), 'blogs');

        return $this->apiResponse(message: __("response.blog.created"), data: $blog);
    }

    public function show(BlogShowRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(data: $resource);
    }

    public function update(BlogUpdateRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $this->blogService->update($blog->id, $data);

        if($request->hasFile('image')) {
            $blog->storeFeaturedImage($request->file('image'), 'blogs');
        }

        return $this->apiResponse(data: $blog);
    }

    public function updateLogo(UpdateLogoRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $logos = $request->logos ?? null;

        $this->blogService->update($blog->id, ['Logos' => $logos]);

        return $this->apiResponse(message: __("response.blog.logo_updated"));
    }

    public function destroy(BlogDestroyRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $this->blogService->destroy($blog);

        return $this->apiResponse(message: __("response.blog.destroyed"));
    }

    public function changeStatus(BlogChangeStatusRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $blog->status = 'active';
        $blog->save();

        return $this->apiResponse(message: __("response.blog.change_status", ['status' => $request->status_translate]));
    }

}
