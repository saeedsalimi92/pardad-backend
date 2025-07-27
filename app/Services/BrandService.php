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


            if($request->hasFile('')) {
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

    public function changePriority($id, $priority): int
    {
        DB::beginTransaction();
        try {
            $brand = $this->brandRepository->findById($id);

            if (!$brand) {
                throw new \Exception("Brand not found.");
            }

            if ($brand->priority == $priority) {
                DB::rollBack(); // نیازی به تغییر نیست
                return 0;
            }

            // شیفت دادن برندهایی که از قبل این اولویت رو دارند
            Brand::where('priority', '>=', $priority)
                ->where('id', '<>', $brand->id)
                ->orderBy('priority', 'desc')
                ->get()
                ->each(function ($item) {
                    $item->priority += 1;
                    $item->save();
                });

            // تغییر اولویت برند اصلی
            $this->brandRepository->update($brand->id, ['priority' => $priority]);

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Change Priority Error: ' . $e->getMessage());
            return 0;
        }
    }

}
