<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Brand\BrandChangePriorityRequest;
use App\Http\Requests\Admin\V1\Brand\BrandChangeStatusRequest;
use App\Http\Requests\Admin\V1\Brand\BrandDestroyRequest;
use App\Http\Requests\Admin\V1\Brand\BrandIndexRequest;
use App\Http\Requests\Admin\V1\Brand\BrandShowRequest;
use App\Http\Requests\Admin\V1\Brand\BrandStoreRequest;
use App\Http\Requests\Admin\V1\Brand\BrandUpdateRequest;
use App\Http\Resources\Admin\V1\Brand\BrandShowResource;
use App\Http\Resources\PaginationResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\BrandService;

class BrandController extends Controller
{
    public function __construct(private BrandService $brandService)
    {
    }

    public function index(BrandIndexRequest $request)
    {
        $data = $request->validated();
        $perPage = $request->input('per_page', 15);
        $categories = $this->brandService->index($data , $perPage);

        $resource = BrandShowResource::collection($categories);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($categories));
    }

    public function store(BrandStoreRequest $request)
    {
        $brand = $this->brandService->create($request);
        if(!$brand) {
            return $this->apiResponse(success: false, message: "response.brand.error", httpStatusCode: 500);
        }

        $brand->update(['priority' => $brand->id]);
        $resource = BrandShowResource::make($brand);
        return $this->apiResponse(message: __("response.brand.created"), data: $resource);
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

    public function update($id, BrandUpdateRequest $request)
    {
        $brand = $this->brandService->findById($id);
        if(is_null($brand)) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        $this->brandService->update($brand, $request);

        $brand->refresh();

        $resource = BrandShowResource::make($brand);

        return $this->apiResponse(data: $resource);
    }

    public function destroy($id, BrandDestroyRequest $request)
    {
        $brand = $this->brandService->findById($id);
        if(is_null($brand)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->brandService->destroy($brand);

        return $this->apiResponse(message: __("response.brand.destroyed"));
    }

    public function changeStatus($id, BrandChangeStatusRequest $request): \Illuminate\Http\JsonResponse
    {
        $brand = $this->brandService->findById($id);
        if(is_null($brand)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->brandService->changeStatus($brand->id, $request->status);

        return $this->apiResponse(message: __("response.brand.change_status", ['status' => $brand->status_translate]));
    }

    public function changePriority(BrandChangePriorityRequest $request): \Illuminate\Http\JsonResponse
    {
        $orderedIds = $request->input('list'); // فرض بر اینکه name فیلد priority هست

        if (!is_array($orderedIds) || empty($orderedIds)) {
            return $this->apiResponse(httpStatusCode: 422, message: __('Invalid input.'));
        }

        $this->brandService->changePriority($orderedIds);

        return $this->apiResponse(message: __("response.brand.change_priority"));
    }


}


