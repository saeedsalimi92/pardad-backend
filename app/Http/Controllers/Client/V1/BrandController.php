<?php

namespace App\Http\Controllers\Client\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;

use App\Http\Requests\Client\V1\Brand\BrandIndexRequest;
use App\Http\Requests\Client\V1\Brand\BrandShowRequest;
use App\Http\Resources\Client\V1\Brand\BrandShowResource;
use App\Http\Resources\PaginationResource;
use Illuminate\Support\Facades\Cache;

use App\Services\BrandService;

class BrandController extends Controller
{
    public function __construct(private readonly BrandService $brandService)
    {
    }

    public function index(BrandIndexRequest $request)
    {
        $data = $request->validated();
        $data["status"] = Status::Active->value;
        // ساخت کلید کش
        $cacheKey = 'brands_index_' . md5(json_encode($data));

        $brands = Cache::remember($cacheKey, now()->addHours(24), function () use ($data) {
            return $this->brandService->index($data);
        });

        $resource = BrandShowResource::collection($brands);

        return $this->apiResponse(data: $resource);
    }

    public function show($id, BrandShowRequest $request)
    {
        $brand = $this->brandService->findById($id);
        if(is_null($brand)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $resource = BrandShowResource::make($brand);

        return $this->apiResponse(data: $resource);
    }



}


