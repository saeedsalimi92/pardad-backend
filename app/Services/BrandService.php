<?php

namespace App\Services;

use App\Enums\BrandType;
use App\Http\Requests\Admin\V1\Brand\BrandStoreRequest;
use App\Http\Requests\Admin\V1\Brand\BrandUpdateRequest;
use App\Models\Brand;
use App\Repositories\BrandRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class BrandService
{
    public function __construct(private BrandRepositoryInterface $brandRepository)
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
                'author_id' => auth()->id(),
                'type' => $data["type"] ?? BrandType::Normal->value,
                'parent_id' => $data["parent_id"],
                'code' => generateShortUniqueCode()
            ]);

            foreach ($data['contents'] as $content) {
                $brand->contents()->create([
                    'locale' => $content["locale"],
                    'title' => $content['title'],
                    'slug' => $content['slug'],
                    'content' => $content['content'],
                    'props' => $content['props'] ?? [],
                ]);
            }

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

    public function changeStatus($id, $status): int
    {
        return $this->brandRepository->update($id, ["status" => $status]);
    }

    public function update(Brand $brand, BrandUpdateRequest $request): bool
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $this->brandRepository->update($brand->id, [
                'status' => $data['status'],
                'parent_id' => $data["parent_id"],
                'type' => $data["type"],
            ]);

            foreach ($data['contents'] as $content) {

                $data = [
                    'title' => $content['title'],
                    'content' => $content['content'],
                    'props' => $content['props'] ?? [],
                ];

                if(isset($content["slug"]) && !is_null($content["slug"])) {
                    $data["slug"] = $content["slug"];
                }

                $brand->contents()->where("locale", $content["locale"])->update($data);
            }

            if($request->hasFile('image')) {
                $brand->storeFeaturedImage($request->file('image'), 'brand');
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return false;
        }
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->brandRepository->getList($data);
    }

    public function destroy($brand)
    {
        foreach ($brand->images as $image) {
            $brand->deleteImage($image->id);
        }

        return $brand->delete();
    }

    public function findByLocale($code, $data)
    {
        return $this->brandRepository->findByLocale($code, $data);
    }
}
