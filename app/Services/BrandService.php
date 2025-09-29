<?php

namespace App\Services;


use App\Http\Requests\Admin\V1\Brand\BrandStoreRequest;
use App\Http\Requests\Admin\V1\Brand\BrandUpdateRequest;
use App\Models\Brand;
use App\Repositories\BrandRepositoryInterface;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class BrandService
{
    public function __construct(private readonly BrandRepositoryInterface $brandRepository)
    {
    }
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->brandRepository->index($data, $perPage);
    }

    public function findById($id, $with=[])
    {
        return $this->brandRepository->findById($id, $with);
    }

    public function create(BrandStoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $brand = $this->brandRepository->create([
                'status' => $data['status'],
                'name' => $data['name'],
                'file' => $data['file'],
                'action' => $data['action'],
                'priority' => $data['priority'],
            ]);

            if($request->hasFile('image')) {
                $brand->storeFeaturedImage($request->file('image'), 'brand');
            }

            DB::commit();

            return $brand;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return null;
        }
    }

    public function update(Brand $brand, BrandUpdateRequest $request): bool
    {


        $data = $request->validated();

        DB::beginTransaction();
        try {
            $this->brandRepository->update($brand->id, [
                'status' => $data['status'],
                'name' => $data['name'],
                'file' => $data['file'],
                'action' => $data['action'],
                'priority' => $data['priority'],
            ]);


            if($request->hasFile('image')) {
                $brand->storeFeaturedImage($request->file('image'), 'brand');
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            // برای تست
            return false;
        }
    }


    public function destroy($brand)
    {
        foreach ($brand->images as $image) {
            $brand->deleteImage($image->id);
        }
        return $brand->delete();
    }

    public function changeStatus($id, $status): int
    {
        return $this->brandRepository->update($id, ["status" => $status]);
    }

    public function changePriority($orderedIds): int
    {
        DB::beginTransaction();
        try {
            foreach ($orderedIds as $index => $id) {
                $priority = $index + 1; // چون اولویت باید از 1 شروع بشه
                Brand::where('id', $id)->update(['priority' => $priority]);
            }

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Priorities Error: ' . $e->getMessage());
            return 0;
        }
    }

}
