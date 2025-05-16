<?php

namespace App\Services;

use App\Http\Requests\Admin\V1\Blog\BlogStoreRequest;
use App\Repositories\BlogRepositoryInterface;
use Illuminate\Support\Facades\DB;

readonly class BlogService
{
    public function __construct(private BlogRepositoryInterface $blogRepository)
    {
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->blogRepository->index($data, $perPage);
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
                'status' => $data['status'],
                'published_at' => $data['published_at'],
                'props' => $data['props'] ?? null,
                'author_id' => auth()->id()
            ]);

            foreach ($data['contents'] as $locale => $content) {
                $blog->contents()->create([
                    'locale' => $locale,
                    'title' => $content['title'],
                    'content' => $content['content'],
                ]);

            }

            if (isset($data['related_blogs'])) {
                $blog->relatedBlogs()->sync($data['related_blogs']);
            }

            $blog->storeFeaturedImage($request->file('image'), 'blogs');

            DB::commit();

            return $blog;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function update($blog, $data): int
    {
        $data['url'] = generateUrl($data['url'] ?? null, $data['title']);

        if (empty($data['canonical'])) {
            $data['canonical'] = generateSlug($data['url']);
        }

        $this->blogRepository->update($blog->id, $data);

        if($data["related_blog_ids"]) {
            $blog->relatedBlogs()->sync($data["related_blog_ids"]);
        }

        return $blog;
    }

    public function destroy($blog)
    {
        foreach ($blog->images as $image) {
            $blog->deleteImage($image->id);
        }

        return $blog->delete();
    }
}
