<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\V1\Redirect\RedirectChangeStatusRequest;
use App\Http\Requests\Admin\V1\Redirect\RedirectDestroyRequest;
use App\Http\Requests\Admin\V1\Redirect\RedirectIndexRequest;
use App\Http\Requests\Admin\V1\Redirect\RedirectShowRequest;
use App\Http\Requests\Admin\V1\Redirect\RedirectStoreRequest;
use App\Http\Requests\Admin\V1\Redirect\RedirectUpdateRequest;
use App\Http\Resources\Admin\V1\Redirect\RedirectIndexResource;
use App\Http\Resources\Admin\V1\Redirect\RedirectShowResource;
use App\Http\Resources\PaginationResource;
use App\Services\RedirectService;
use Illuminate\Support\Facades\Cache;



class RedirectController extends Controller
{
    public function __construct(private RedirectService $redirectService)
    {
    }

    public function index(RedirectIndexRequest $request)
    {
        $data = $request->validated();
        $perPage = $request->input('per_page', 15);
        $categories = $this->redirectService->index($data , $perPage);

        $resource = RedirectIndexResource::collection($categories);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($categories));
    }

    public function store(RedirectStoreRequest $request)
    {
        $redirect = $this->redirectService->create($request);
        if(!$redirect) {
            return $this->apiResponse(success: false, message: "response.redirect.error", httpStatusCode: 500);
        }

        $resource = RedirectShowResource::make($redirect);
        $this->clearAllRedirectCacheKeys();
        return $this->apiResponse(message: __("response.redirect.created"), data: $resource);
    }

    public function show($id, RedirectShowRequest $request)
    {
        $redirect = $this->redirectService->findById($id);
        if(is_null($redirect)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $resource = RedirectShowResource::make($redirect);

        return $this->apiResponse(data: $resource);
    }

    public function update($id, RedirectUpdateRequest $request)
    {
        $redirect = $this->redirectService->findById($id);
        if(is_null($redirect)) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        $this->redirectService->update($redirect, $request);

        $redirect->refresh();

        $this->clearAllRedirectCacheKeys();

        $resource = RedirectShowResource::make($redirect);

        return $this->apiResponse(data: $resource);
    }

    public function destroy($id, RedirectDestroyRequest $request)
    {
        $redirect = $this->redirectService->findById($id);
        if(is_null($redirect)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->redirectService->destroy($redirect);

        $this->clearAllRedirectCacheKeys();
        return $this->apiResponse(message: __("response.redirect.destroyed"));
    }

    public function changeStatus($id, RedirectChangeStatusRequest $request): \Illuminate\Http\JsonResponse
    {
        $redirect = $this->redirectService->findById($id);
        if(is_null($redirect)) {
            return $this->apiResponse(httpStatusCode: 404);
        }


        $this->redirectService->changeStatus($redirect->id, $request->status);
        $redirect->refresh();

        return $this->apiResponse(message: __("response.redirect.change_status", ['status' => $redirect->status_translate]));
    }

    protected function cacheKeyList(): string
    {
        return 'redirects.cache_keys';
    }

    protected function addCacheKey(string $key): void
    {
        $keys = Cache::get($this->cacheKeyList(), []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            Cache::put($this->cacheKeyList(), $keys, now()->addHours(24));
        }
    }

    protected function clearAllRedirectCacheKeys(): void
    {
        $keys = Cache::get($this->cacheKeyList(), []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget($this->cacheKeyList());
    }



}


