<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Validator;

class BlogController extends Controller
{

    private function filterAndPaginateBlogs(Request $request, $onlyActive = false)
    {
        $perPage = $request->input('per_page', 15);

        $query = Blog::query();

        // 🔍 فیلتر بر اساس name
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // 🔍 فیلتر بر اساس status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔍 فیلتر بر اساس parent_id
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        // 🔍 فیلتر بر اساس parent_type
        if ($request->filled('parent_type')) {
            $query->where('parent_type', $request->parent_type);
        }

        // 🔒 اگر فقط وضعیت active لازم باشد
        if ($onlyActive) {
            $query->where('status', 'active');
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return $blogs;
    }



    private function getBlogsList(Request $request, $onlyActive = false, $filterByParent = false)
    {
        $query = Blog::query();

        if ($onlyActive) {
            $query->where('status', 'active');
        }

        if ($filterByParent) {
            $query->where('parent_type', $request->parent_type)
                ->where('parent_id', $request->parent_id);
        }

        // فیلترهای داینامیک مشابه مرحله قبل
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        if ($request->filled('parent_type')) {
            $query->where('parent_type', $request->parent_type);
        }

        $blogs = $query->orderBy('created_at', 'desc')->get();

        return $blogs;
    }



    public function index(Request $request)
    {
        return response()->json($this->filterAndPaginateBlogs($request));
    }

    public function client_index(Request $request)
    {
        return response()->json($this->filterAndPaginateBlogs($request, true));
    }



    public function list(Request $request)
    {
        return response()->json($this->getBlogsList($request));
    }



    public function listByParent(Request $request)
    {
        return response()->json($this->getBlogsList($request, false, true));
    }

    public function client_listByParent(Request $request)
    {
        return response()->json($this->getBlogsList($request, true, true));
    }





    private function getBlogsByParent(Request $request, $onlyActive = false)
    {
        $perPage = $request->input('per_page', 15);

        $query = Blog::query();

        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        if ($request->has('parent_type')) {
            $query->where('parent_type', $request->parent_type);
        }

        if ($onlyActive) {
            $query->where('status', 'active');
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return $blogs;
    }

    public function indexByParent(Request $request)
    {
        return response()->json($this->getBlogsByParent($request));
    }

    public function client_indexByParent(Request $request)
    {
        return response()->json($this->getBlogsByParent($request, true));
    }



    private function validateBlog(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'type' => 'nullable|string|max:255',
            'canonical' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'parent_type' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
        ]);
    }

    private function generateCanonical(string $title): string
    {
        return Str::slug($title); // تولید URL-friendly slug از name
    }


    public function store(Request $request)
    {

        $data = $this->validateBlog($request);

        $data['url'] = $this->generateUrl($data['url'] ?? null, $data['title']);

        if (empty($data['canonical'])) {
            $data['canonical'] = $this->generateCanonical($data['url']);
        }

        $blog = Blog::create($data);

        return response()->json($blog);
    }

/*    public function store(Request $request)
    {

        $messages = [
            'title.required' => 'عنوان الزامی است.',
            'title.min' => 'عنوان باید حداقل ۴ کاراکتر باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۴ کاراکتر باشد.',
            'parent_id.required' => 'زیرشاخه الزامی است.',
            'parent_type.required' => 'نوع والد الزامی است.',
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'parent_id' => 'required',
            'parent_type' => 'required|in:category,sub_category', // اضافه کردن اعتبارسنجی برای parent_type
            'description' => 'required|min:4',
        ], $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 422);
        }

        $data = [];
        $data['url'] = $this->generateUrl($request->url, $request->title);
        $image = [];

        // بررسی وجود parent بر اساس parent_type
        $parentExists = false;
        if ($request->parent_type === 'category') {
            $parentExists = Category::where('id', $request->parent_id)->exists();
        } elseif ($request->parent_type === 'sub_category') {
            $parentExists = SubCategory::where('id', $request->parent_id)->exists();
        }

        if (!$parentExists) {
            return response()->json(['error' => 'شناسه والد یافت نشد.'], 422);
        }

        $blog = new Blog;
        $blog->title = $request->title;
        $blog->url = $data['url'];
        $blog->en_title = $request->en_title ?? '';
        $blog->title = $request->title ?? '';
        $blog->en_subtitle = $request->en_subtitle ?? '';
        $blog->subtitle = $request->subtitle ?? '';
        $blog->en_description = $request->en_description ?? '';
        $blog->description = $request->description ?? '';
        $blog->status = $request->status ?? 'inactive';
        $blog->meta_description = $request->meta_description ?? '';
        $blog->meta_title = $request->meta_title ?? '';
        $blog->meta_keywords = $request->meta_keywords ?? '';
        $blog->canonical = $request->canonical ?? '';
        $blog->no_index = $request->no_index ?? 'inactive';
        $blog->parent_id = $request->parent_id ?? '';
        $blog->parent_type = $request->parent_type ?? 'sub_category'; // مقدار پیش‌فرض
        $blog->images = json_encode($image);
        $blog->logos = json_encode($image);
        $blog->instagram = $request->instagram ?? '';
        $blog->telegram = $request->telegram ?? '';
        $blog->whatsapp = $request->whatsapp ?? '';
        $blog->author = $request->author ?? '';
        $blog->save();


        $id = $blog->latest()->first()->id;
        if ($files = $request->file('image')) {
            foreach ($files as $file) {
                $path = (new ImagesController)->store($file, 'images', 'category', $id);
                $image['images'][] = $path->original;
            }
        }
        $blog->images = json_encode($image);

        return response()->json([
            "message" => " بلاگ با موفقیت اضافه شد."
        ], 201);

    }*/

  /*  public function show($id)
    {
        $blog = Blog::find($id);
        if (!empty($blog)) {
            $data = array();
            $image = (new ImagesController)->showByParent('blog', $id);
            $blog->images = json_decode($image);


            $blog->images = json_decode($image);
            $blog->logos = is_null($blog->logos) ? Null : json_decode($blog->logos);


            foreach ($blog->logos as $item) {
                $logos = (new LogoController)->show($item);
                array_push($data, $logos->original);
            }


            $blog->logos_image = $data;

            return response()->json($blog);
        } else {
            return response()->json([
                "message" => " بلاگ یافت نشد"
            ], 404);
        }
    }*/
    public function show($id)
    {
        $blog = Blog::with(['comments.replies', 'faqs'])->find($id);

        if (!$blog) {
            return response()->json([
                "message" => "بلاگ یافت نشد"
            ], 404);
        }


        return response()->json($blog);
    }


    public function client_show($id)
    {
        // ابتدا بررسی می‌کنیم که بلاگ وجود دارد و فعال است
        $blog = Blog::where('status', 'active')->find($id);

        if (!$blog) {
            return response()->json([
                "message" => "بلاگ یافت نشد یا غیرفعال است"
            ], 404);
        }

        // سپس بلاگ را با کامنت‌های تایید شده و پاسخ‌های تایید شده بارگذاری می‌کنیم
        $blog->load([
            'comments' => function($query) {
                $query->where('status', 'approved')
                    ->whereNull('parent_id')
                    ->with(['replies' => function($q) {
                        $q->where('status', 'approved');
                    }]);
            },
            'faqs'
        ]);

        return response()->json($blog);

    }

    public function update(Request $request, Blog $blog)
    {
        $data = $this->validateBlog($request);

        $data['url'] = $this->generateUrl($data['url'] ?? null, $data['title']);
        if (empty($data['canonical'])) {
            $data['canonical'] = $this->generateCanonical($data['url']);
        }

        $blog->update($data);

        if ($request->hasFile('image')) {
            $this->saveImage($request, $blog, 'blog');
        }

        return response()->json($blog->load('image'));
    }
   /* public function update(Request $request, $id)
    {
        if (Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);
            $data = $request->all();
            $data['url'] = $this->generateUrl($request->url, $request->title);

            $blog->url = $data['url'];
            $blog->title = $request->has('title') ? (!empty($request->title) ? $request->title : '') : ($blog->title ?? '');
            $blog->en_title = $request->has('en_title') ? (!empty($request->en_title) ? $request->en_title : '') : ($blog->en_title ?? '');
            $blog->en_subtitle = $request->has('en_subtitle') ? (!empty($request->en_subtitle) ? $request->en_subtitle : '') : ($blog->en_subtitle ?? '');
            $blog->subtitle = $request->has('subtitle') ? (!empty($request->subtitle) ? $request->subtitle : '') : $blog->subtitle;
            $blog->en_description = $request->has('en_description') ? (!empty($request->en_description) ? $request->en_description : '') : $blog->en_description;
            $blog->description = $request->has('description') ? (!empty($request->description) ? $request->description : '') : $blog->description;
            $blog->meta_description = $request->has('meta_description') ? (!empty($request->meta_description) ? $request->meta_description : '') : $blog->meta_description;
            $blog->meta_title = $request->has('meta_title') ? (!empty($request->meta_title) ? $request->meta_title : '') : $blog->meta_title;
            $blog->meta_keywords = $request->has('meta_keywords') ? (!empty($request->meta_keywords) ? $request->meta_keywords : '') : $blog->meta_keywords;
            $blog->canonical = $request->has('canonical') ? (!empty($request->canonical) ? $request->canonical : '') : $blog->canonical;
            $blog->no_index = $request->has('no_index') ? (!empty($request->no_index) ? $request->no_index : 'inactive') : $blog->no_index;
            $blog->status = $request->has('status') ? (!empty($request->status) ? $request->status : 'inactive') : $blog->status;
            $blog->parent_id = $request->has('parent_id') ? (!empty($request->parent_id) ? $request->parent_id : '') : $blog->parent_id;
            $blog->parent_type = $request->has('parent_type') ? (!empty($request->parent_type) ? $request->parent_type : 'sub_category') : $blog->parent_type;
            $blog->telegram = $request->has('telegram') ? (!empty($request->telegram) ? $request->telegram : '') : $blog->telegram;
            $blog->instagram = $request->has('instagram') ? (!empty($request->instagram) ? $request->instagram : '') : $blog->instagram;
            $blog->whatsapp = $request->has('whatsapp') ? (!empty($request->whatsapp) ? $request->whatsapp : '') : $blog->whatsapp;
            $blog->author = $request->has('author') ? (!empty($request->author) ? $request->author : '') : $blog->author;

            $blog->save();
            return response()->json([
                "message" => "بلاگ با موفقیت بروزرسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => "بلاگ یافت نشد."
            ], 404);
        }
    }*/

    public function updateLogos(Request $request, $id)
    {
        if (Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);

            Log::info($request->logos);

            $blog->Logos = is_null($request->logos) ? Null : $request->logos;


            $blog->save();
            return response()->json([
                "message" => " لوگوی خبر با موفقیت بروزرسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {

    }

    public function deleteImage(Request $request, $blog, $id)
    {


    }

    public function destroy(Blog $blog)
    {
        $this->deleteImage($blog);
        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully.']);
    }

    public function restore(Blog $blog)
    {
        $blog->status = 'active';
        $blog->save();

        return response()->json(['message' => 'Blog restored successfully.']);
    }

    public function archive(Blog $blog)
    {
        $blog->status = 'inactive';
        $blog->save();

        return response()->json(['message' => 'Blog archived successfully.']);
    }

    /*public function destroy($id)
    {
        if (Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);
            $images = json_decode((new ImagesController)->showByParent('blog', $id), true);
            $ids = array();
            if (key($images['images']) === 'id') {
                foreach ($images['images'] as $file) {
                    array_push($ids, $file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $blog->delete();

            return response()->json([
                "message" => " بلاگ با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }*/

    private function generateUrl($url, $title)
    {
        return $url ? str_replace(' ', '-', $url) : str_replace(' ', '-', $title);
    }
}
