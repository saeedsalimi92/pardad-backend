<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Validator;
use function PHPUnit\Framework\isEmpty;

class CategoryController extends Controller
{

    public function index()
    {
        $category = Category::orderBy('created_at', 'desc')->paginate(15);
        foreach ($category as $item) {
            $image = (new ImagesController)->showByParent('category', $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($category);
    }

    public function list()
    {
        Log::info("test");
        $category = Category::all();
        $data = [];

        foreach ($category as $item) {

            $data[] = (object)[
                'title' => $item->title,
                'url' => $item->url,
                'id' => $item->id,
                'value' => $item->id,
            ];

        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        // پیام‌های خطا
        $messages = [
            'title.required' => 'عنوان الزامی است.',
            'title.min' => 'عنوان باید حداقل ۴ کاراکتر باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۴ کاراکتر باشد.',
        ];

        // اعتبارسنجی فیلدهای الزامی
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'description' => 'required|min:4',
        ], $messages);

        // اگر اعتبارسنجی با خطا مواجه شد
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 422);
        }
        $data = [];
        // تولید URL
        $data['url'] = $this->generateUrl($request->url, $request->title);
        $image = [];

        // ایجاد دسته‌بندی جدید
        $category = new Category;
        $category->title = $request->title;
        $category->url = $data['url'];
        $category->description = $request->description;

        // تنظیم مقادیر پیش‌فرض برای فیلدهای اختیاری
        $category->en_title = $request->en_title ?? '';
        $category->en_subtitle = $request->en_subtitle ?? '';
        $category->subtitle = $request->subtitle ?? '';
        $category->en_description = $request->en_description ?? '';
        $category->status = $request->status ?? 'inactive';
        $category->meta_description = $request->meta_description ?? '';
        $category->meta_title = $request->meta_title ?? '';
        $category->meta_keywords = $request->meta_keywords ?? '';
        $category->canonical = $request->canonical ?? '';
        $category->no_index = $request->no_index ?? 'inactive';
        $category->images = json_encode($image);
        $category->logos = json_encode($image);
        $category->save();

        $id = $category->latest()->first()->id;
        if ($files = $request->file('image')) {
            foreach ($files as $file) {
                $path = (new ImagesController)->store($file, 'images', 'category', $id);
                $image['images'][] = $path->original;
            }
        }
        $category->images = json_encode($image);


        $category->save();
        return response()->json([
            "message" => " دسته بندی با موفقیت اضافه شد."
        ], 201);
    }


    public function show($id)
    {
        $category = Category::find($id);
        if (!empty($category)) {
            $data = array();
            $image = (new ImagesController)->showByParent('category', $id);
            $category->images = json_decode($image);

            $category->images = json_decode($image);
            $category->logos = is_null($category->logos) ? Null : json_decode($category->logos);


            foreach ($category->logos as $item) {
                $logos = (new LogoController)->show($item);
                array_push($data, $logos->original);
            }


            $category->logos_image = $data;



            return response()->json($category);
        } else {
            return response()->json([
                "message" => " دسته بندی یافت نشد"
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {

        // پیام‌های خطا
        $messages = [
            'title.required' => 'عنوان الزامی است.',
            'id.required' => 'شناسه دسته بندی الزامی است.',
            'title.min' => 'عنوان باید حداقل ۴ کاراکتر باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۴ کاراکتر باشد.',
        ];

        // اعتبارسنجی فیلدهای الزامی
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'description' => 'required|min:4',
        ], $messages);

        // اگر اعتبارسنجی با خطا مواجه شد
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 422);
        }


        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);

            $category->title = $request->has('title') ? (!empty($request->title) ? $request->title : '') : ($category->title ?? '');
            $category->en_title = $request->has('en_title') ? (!empty($request->en_title) ? $request->en_title : '') : ($category->en_title ?? '');
            $category->en_subtitle = $request->has('en_subtitle') ? (!empty($request->en_subtitle) ? $request->en_subtitle : '') : ($category->en_subtitle ?? '');
            $category->subtitle = $request->has('subtitle') ? (!empty($request->subtitle) ? $request->subtitle : '') : $category->subtitle;
            $category->en_description = $request->has('en_description') ? (!empty($request->en_description) ? $request->en_description : '') : $category->en_description;
            $category->description = $request->has('description') ? (!empty($request->description) ? $request->description : '') : $category->description;
            $category->meta_description = $request->has('meta_description') ? (!empty($request->meta_description) ? $request->meta_description : '') : $category->meta_description;
            $category->meta_title = $request->has('meta_title') ? (!empty($request->meta_title) ? $request->meta_title : '') : $category->meta_title;
            $category->meta_keywords = $request->has('meta_keywords') ? (!empty($request->meta_keywords) ? $request->meta_keywords : '') : $category->meta_keywords;
            $category->canonical = $request->has('canonical') ? (!empty($request->canonical) ? $request->canonical : '') : $category->canonical;
            $category->no_index = $request->has('no_index') ? (!empty($request->no_index) ? $request->no_index : 'inactive') : $category->no_index;
            $category->status = $request->has('status') ? (!empty($request->status) ? $request->status : 'inactive') : $category->status;

            $data = $request->all();
            $data['url'] = $this->generateUrl($request->url, $request->title);
            $category->url = $data['url'];



            $category->save();

            return response()->json([
                "message" => "دسته بندی با موفقیت بروزرسانی شد."
            ], 200);
        } else {
            return response()->json([
                "message" => "دسته بندی یافت نشد."
            ], 404);
        }
    }

    private function generateUrl($url, $title)
    {
        return $url ? str_replace(' ', '-', $url) : str_replace(' ', '-', $title);
    }

    public function updateLogos(Request $request, $id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);

            Log::info($request->logos);

            $category->Logos = is_null($request->logos) ? '' : $request->logos;


            $category->save();
            return response()->json([
                "message" => " لوگوی خبر با موفقیت بروزرسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " دسته بندی یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->images = (new ImagesController)->showByParent('category', $id);
            $data = [
                'images' => json_decode($category->images)->images,
            ];
            $image = json_decode(($category->images), true);

            if ($request->has_images == "true") {

                if ($files = $request->file('images')) {
                    foreach ($files as $file) {


                        $path = (new ImagesController)->store($file, 'images', 'category', $id);
                        foreach ($image as $key => $value) {
                            if ($key == 'images') {
                                array_push($image['images'], $path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }


            $category->images = $data;
            $category->save();
            return response()->json([
                "message" => " دسته بندی با موفقیت بروز رسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " دسته بندی یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request, $category, $id)
    {

        if (Category::where('id', $category)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " دسته بندی یافت نشد."
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $images = json_decode((new ImagesController)->showByParent('category', $id), true);
            $ids = array();
            if (key($images['images']) === 'id') {
                foreach ($images['images'] as $file) {
                    array_push($ids, $file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $category->delete();

            return response()->json([
                "message" => " دسته بندی با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " دسته بندی یافت نشد."
            ], 404);
        }
    }
}
