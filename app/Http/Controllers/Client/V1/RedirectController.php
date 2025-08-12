<?php

namespace App\Http\Controllers\Client\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;

use App\Http\Requests\Client\V1\Redirect\RedirectIndexRequest;
use App\Http\Requests\Client\V1\Redirect\RedirectShowRequest;
use App\Http\Resources\Client\V1\Redirect\RedirectShowResource;
use App\Http\Resources\PaginationResource;

use App\Services\RedirectService;
use Illuminate\Support\Facades\Cache;
class RedirectController extends Controller
{
    public function __construct(private readonly RedirectService $redirectService)
    {
    }

    public function index(RedirectIndexRequest $request)
    {
        $cacheKey = 'redirects.index.' . md5(json_encode($request->validated()));
        $expiresIn = now()->addHours(24); // 12 ساعت
        $this->addCacheKey($cacheKey);

        return Cache::remember($cacheKey, $expiresIn, function () use ($request) {
            $data = $request->validated();
            $data["status"] = Status::Active->value;
            $redirects = $this->redirectService->index($data);

            $resource = RedirectShowResource::collection($redirects);

            return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($redirects));

        });
    }


    public function show($id, RedirectShowRequest $request)
    {
        $cacheKey = 'redirects.show.' . $id;
        $expiresIn = now()->addHours(12); // 12 ساعت

        return Cache::remember($cacheKey, $expiresIn, function () use ($id, $request) {
            $redirect = $this->redirectService->findById($id);

            if (is_null($redirect)) {
                return $this->apiResponse(httpStatusCode: 404);
            }

            $resource = RedirectShowResource::make($redirect);

            return $this->apiResponse(data: $resource);
        });
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

}


