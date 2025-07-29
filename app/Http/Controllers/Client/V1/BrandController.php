<?php

namespace App\Http\Controllers\Client\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;

use App\Http\Requests\Client\V1\Brand\BrandIndexRequest;
use App\Http\Requests\Client\V1\Brand\BrandShowRequest;
use App\Http\Resources\Client\V1\Brand\BrandShowResource;
use App\Http\Resources\PaginationResource;

use App\Services\BrandService;

class BrandController extends Controller
{

    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }



    public function index(BrandIndexRequest $request)
    {
        $data = $request->validated();
        $data["status"] = Status::Active->value;
        $brands = $this->brandService->index($data);

        $resource = BrandShowResource::collection($brands);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($brands));
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


