<?php

namespace App\Services;

use App\Enums\Status;
use App\Http\Requests\Admin\V1\Product\ProductStoreRequest;
use App\Http\Requests\Admin\V1\Product\ProductUpdateRequest;
use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class ProductService
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->productRepository->index($data, $perPage);
    }

    public function findById($id, $with = [])
    {
        return $this->productRepository->findById($id, $with);
    }

    public function findByCode($code, $with=[])
    {
        return $this->productRepository->findByCode($code, $with);
    }

    public function findByLocale($code, $data)
    {
        return $this->productRepository->findByLocale($code, $data);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->productRepository->getList($data);
    }

    public function create(ProductStoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $product = $this->productRepository->create([
                'category_id' => $data['category_id'],
                'status' => $data['status'],
                'author_id' => auth()->id(),
                'code' => generateShortUniqueCode(),
                'props' => $data['props'] ?? [],

            ]);

            foreach ($data['contents'] as $content) {
                $product->contents()->create([
                    'locale' => $content["locale"],
                    'title' => $content['title'],
                    'slug' => $content['slug'],
                    'content' => $content['content'],
                    'props' => $content['props'] ?? [],
                ]);
            }

            if($request->hasFile('image')) {
                $product->storeFeaturedImage($request->file('image'), 'product');
            }
            if($request->hasFile('gallery')) {
                $product->storeGalleryImage($request->file('gallery'), 'product');
            }

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return null;
        }
    }

    public function changeStatus($id, $status): int
    {
        return $this->productRepository->update($id, ["status" => $status]);
    }

    public function update(Product $product, ProductUpdateRequest $request): bool
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $this->productRepository->update($product->id, [
                'category_id' => $data['category_id'],
                'status' => $data['status'],
                'author_id' => auth()->id(),
                'props' => $data['props'] ?? [],
            ]);

            foreach ($data['contents'] as $content) {

                $item = [
                    'title' => $content['title'],
                    'content' => $content['content'],
                    'props' => $content['props'] ?? [],
                ];

                if(isset($content["slug"]) && !is_null($content["slug"])) {
                    $item["slug"] = $content["slug"];
                }

                $product->contents()->where("locale", $content["locale"])->update($item);
            }


            if($request->hasFile('image')) {
                $product->storeFeaturedImage($request->file('image'), 'product');
            }
            if($request->hasFile('gallery')) {
                $product->storeGalleryImage($request->file('gallery'), 'product');
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return false;
        }
    }

    public function destroy($product)
    {
        foreach ($product->images as $image) {
            $product->deleteImage($image->id);
        }

        return $product->delete();
    }

}
