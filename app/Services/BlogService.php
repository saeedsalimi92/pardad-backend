<?php

namespace App\Services;

use App\Enums\BlogStatus;
use App\Http\Requests\Admin\V1\Blog\BlogStoreRequest;
use App\Http\Requests\Admin\V1\Blog\BlogUpdateRequest;
use App\Models\Blog;
use App\Repositories\BlogRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class BlogService
{
    public function __construct(private BlogRepositoryInterface $blogRepository)
    {
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->blogRepository->index($data, $perPage);
    }

    public function findById($id, $with = [])
    {
        return $this->blogRepository->findById($id, $with);
    }

    public function findByCode($code, $with=[])
    {
        return $this->blogRepository->findByCode($code, $with);
    }

    public function findByLocale($code, $data)
    {
        return $this->blogRepository->findByLocale($code, $data);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->blogRepository->getList($data);
    }

    public function create(BlogStoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $blog = $this->blogRepository->create([
                'category_id' => $data['category_id'],
                'status' => $data['status'],
                'published_at' => $data['published_at'],
                'author_id' => auth()->id(),
                'code' => generateShortUniqueCode()
            ]);

            foreach ($data['contents'] as $content) {
                $blog->contents()->create([
                    'locale' => $content["locale"],
                    'title' => $content['title'],
                    'slug' => $content['slug'],
                    'content' => $content['content'],
                    'props' => $content['props'] ?? [],
                ]);
            }

            if (isset($data['related_blog_ids'])) {
                $blog->relatedBlogs()->sync($data['related_blog_ids']);
            }

            if($request->hasFile('image')) {
                $blog->storeFeaturedImage($request->file('image'), 'blog');
            }

            DB::commit();

            return $blog;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return null;
        }
    }

    public function changeStatus($id, $status): int
    {
        $data = ["status" => $status];
        if($status == BlogStatus::Published->value) {
           $data["published_at"] = now();
        }

        return $this->blogRepository->update($id, $data);
    }

    public function update(Blog $blog, BlogUpdateRequest $request): int
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $this->blogRepository->update($blog->id, [
                'category_id' => $data['category_id'],
                'status' => $data['status'],
                'published_at' => $data['published_at'],
                'author_id' => auth()->id()
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

                $blog->contents()->where("locale", $content["locale"])->update($data);
            }

            if (isset($data['related_blog_ids'])) {
                $blog->relatedBlogs()->sync($data['related_blog_ids']);
            }

            if($request->hasFile('image')) {
                $blog->storeFeaturedImage($request->file('image'), 'blog');
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return false;
        }
    }

    public function destroy($blog)
    {
        foreach ($blog->images as $image) {
            $blog->deleteImage($image->id);
        }

        return $blog->delete();
    }

    public function getPublishedBlogsPaginated($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->blogRepository->getPublishedBlogsPaginated($data, $perPage);
    }
}
