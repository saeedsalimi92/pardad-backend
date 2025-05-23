<?php

namespace App\Services;

use App\Http\Requests\Admin\V1\Category\CategoryStoreRequest;
use App\Http\Requests\Admin\V1\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class CategoryService
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->categoryRepository->index($data, $perPage);
    }

    public function findById($id, $with=[])
    {
        return $this->categoryRepository->findById($id, $with);
    }

    public function create(CategoryStoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $category = $this->categoryRepository->create([
                'status' => $data['status'],
                'author_id' => auth()->id(),
                'parent_id' => $data["parent_id"],
                'code' => uniqid()
            ]);

            foreach ($data['contents'] as $content) {
                $category->contents()->create([
                    'locale' => $content["locale"],
                    'title' => $content['title'],
                    'content' => $content['content'],
                    'props' => $content['props'] ?? [],
                ]);
            }

            if($request->hasFile('image')) {
                $category->storeFeaturedImage($request->file('image'), 'category');
            }

            DB::commit();

            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return null;
        }
    }

    public function changeStatus($id, $status): int
    {
        return $this->categoryRepository->update($id, ["status" => $status]);
    }

    public function update(Category $category, CategoryUpdateRequest $request): bool
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $this->categoryRepository->update($category->id, [
                'status' => $data['status'],
                'parent_id' => $data["parent_id"],
            ]);

            foreach ($data['contents'] as $content) {
                $category->contents()->where("locale", $content["locale"])->update([
                    'title' => $content['title'],
                    'content' => $content['content'],
                    'props' => $content['props'] ?? [],
                ]);
            }

            if($request->hasFile('image')) {
                $category->storeFeaturedImage($request->file('image'), 'category');
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
        return $this->categoryRepository->getList($data);
    }

    public function destroy($category)
    {
        foreach ($category->images as $image) {
            $category->deleteImage($image->id);
        }

        return $category->delete();
    }
}
