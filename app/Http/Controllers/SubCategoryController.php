<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Validator;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategory = SubCategory::orderBy('created_at', 'desc')->paginate(15);
        foreach ($subcategory as $item) {
            $image = (new ImagesController)->showByParent('subcategory', $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($subcategory);
    }

    public function client_Index()
    {
        $subcategory = SubCategory::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        foreach ($subcategory as $item) {
            $image = (new ImagesController)->showByParent('subcategory', $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($subcategory);
    }

    public function indexByParent(Request $request)
    {
        $subcategory = SubCategory::where('parent_id', $request->parent_id)->paginate(15);
        return response()->json($subcategory);
    }
    public function client_indexByParent(Request $request)
    {
        $subcategory = SubCategory::where('parent_id', $request->parent_id)->where('status', 'active')->paginate(15);
        return response()->json($subcategory);
    }

    public function list()
    {

        $category = SubCategory::all();
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

    public function client_list()
    {

        $category = SubCategory::where('status', 'active')->get();
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


    public function listByParent(Request $request)
    {

        $category = SubCategory::where('parent_id', $request->parent_id)->where('status', 'active')->get();


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

    public function client_listByParent(Request $request)
    {

        $category = SubCategory::where('parent_id', $request->parent_id)->where('status', 'active')->get();


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

    public function getByParent(Request $request)
    {

        $category = SubCategory::where('parent_id', $request->parent_id)->where('status', 'active')->get();


        return response()->json($category);
    }

    public function store(Request $request)
    {


        // پیام‌های خطا
        $messages = [
            'title.required' => 'عنوان الزامی است.',
            'title.min' => 'عنوان باید حداقل ۴ کاراکتر باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۴ کاراکتر باشد.',
            'parent_id.required' => 'دسته بندی الزامی است.',

        ];

        // اعتبارسنجی فیلدهای الزامی
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'parent_id' => 'required',
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

        $subCategory = Category::find($request->parent_id);
        if (!empty($subCategory)) {
            $category = new SubCategory();
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
            $category->parent_id = $request->parent_id ?? '';
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
                "message" => " زیر شاخه با موفقیت اضافه شد."
            ], 201);
        } else {
            return response()->json([
                "message" => " شناسه دسته بندی یافت نشد."
            ], 404);
        }


    }

    public function show($id)
    {
        $subcategory = SubCategory::find($id);
        if (!empty($subcategory)) {
            $data = array();
            $image = (new ImagesController)->showByParent('subcategory', $id);
            $subcategory->images = json_decode($image);

            $subcategory->images = json_decode($image);
            $subcategory->logos = is_null($subcategory->logos) ? Null : json_decode($subcategory->logos);

            foreach ($subcategory->logos as $item) {
                $logos = (new LogoController)->show($item);
                array_push($data, $logos->original);
            }


            $subcategory->logos_image = $data;


            return response()->json($subcategory);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد"
            ], 404);
        }
    }

    public function client_show($id)
    {
        $subcategory = SubCategory::find($id);
        if (empty($subcategory) || $subcategory->status === 'inactive') {
            return response()->json([
                "message" => "زیر شاخه بندی یافت نشد یا غیرفعال است"
            ], 404);
        }

        $data = array();
        $image = (new ImagesController)->showByParent('subcategory', $id);
        $subcategory->images = json_decode($image);

        $subcategory->images = json_decode($image);
        $subcategory->logos = is_null($subcategory->logos) ? Null : json_decode($subcategory->logos);

        foreach ($subcategory->logos as $item) {
            $logos = (new LogoController)->show($item);
            array_push($data, $logos->original);
        }


        $subcategory->logos_image = $data;


        return response()->json($subcategory);

    }

    public function update(Request $request, $id)
    {

        // پیام‌های خطا
        $messages = [
            'title.required' => 'عنوان الزامی است.',
            'title.min' => 'عنوان باید حداقل ۴ کاراکتر باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۴ کاراکتر باشد.',
            'parent_id.required' => 'دسته بندی الزامی است.',

        ];

        // اعتبارسنجی فیلدهای الزامی
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'parent_id' => 'required',
            'description' => 'required|min:4',
        ], $messages);

        // اگر اعتبارسنجی با خطا مواجه شد
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 422);
        }
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);


            $data = $request->all();
            $data['url'] = $this->generateUrl($request->url, $request->title);
            $subcategory->url = $data['url'];
            $subcategory->title = $request->has('title') ? (!empty($request->title) ? $request->title : '') : ($subcategory->title ?? '');
            $subcategory->en_title = $request->has('en_title') ? (!empty($request->en_title) ? $request->en_title : '') : ($subcategory->en_title ?? '');
            $subcategory->en_subtitle = $request->has('en_subtitle') ? (!empty($request->en_subtitle) ? $request->en_subtitle : '') : ($subcategory->en_subtitle ?? '');
            $subcategory->subtitle = $request->has('subtitle') ? (!empty($request->subtitle) ? $request->subtitle : '') : $subcategory->subtitle;
            $subcategory->en_description = $request->has('en_description') ? (!empty($request->en_description) ? $request->en_description : '') : $subcategory->en_description;
            $subcategory->description = $request->has('description') ? (!empty($request->description) ? $request->description : '') : $subcategory->description;
            $subcategory->meta_description = $request->has('meta_description') ? (!empty($request->meta_description) ? $request->meta_description : '') : $subcategory->meta_description;
            $subcategory->meta_title = $request->has('meta_title') ? (!empty($request->meta_title) ? $request->meta_title : '') : $subcategory->meta_title;
            $subcategory->meta_keywords = $request->has('meta_keywords') ? (!empty($request->meta_keywords) ? $request->meta_keywords : '') : $subcategory->meta_keywords;
            $subcategory->canonical = $request->has('canonical') ? (!empty($request->canonical) ? $request->canonical : '') : $subcategory->canonical;
            $subcategory->no_index = $request->has('no_index') ? (!empty($request->no_index) ? $request->no_index : 'inactive') : $subcategory->no_index;
            $subcategory->status = $request->has('status') ? (!empty($request->status) ? $request->status : 'inactive') : $subcategory->status;
            $subcategory->parent_id = $request->has('parent_id') ? (!empty($request->parent_id) ? $request->parent_id : '') : $subcategory->parent_id;


            $subcategory->save();
            return response()->json([
                "message" => " زیر شاخه با موفقیت بروزرسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    public function updateLogos(Request $request, $id)
    {
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);

            Log::info($request->logos);

            $subcategory->Logos = is_null($request->logos) ? Null : $request->logos;


            $subcategory->save();
            return response()->json([
                "message" => " زیر شاخه با موفقیت بروزرسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);
            $subcategory->images = (new ImagesController)->showByParent('subcategory', $id);
            $data = [
                'images' => json_decode($subcategory->images)->images,
            ];
            $image = json_decode(($subcategory->images), true);

            if ($request->has_images == "true") {

                if ($files = $request->file('images')) {
                    foreach ($files as $file) {


                        $path = (new ImagesController)->store($file, 'images', 'subcategory', $id);
                        foreach ($image as $key => $value) {
                            if ($key == 'images') {
                                array_push($image['images'], $path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }


            $subcategory->images = $data;
            $subcategory->save();
            return response()->json([
                "message" => " زیر شاخه با موفقیت بروز رسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request, $subcategory, $id)
    {

        if (SubCategory::where('id', $subcategory)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);
            $images = json_decode((new ImagesController)->showByParent('subcategory', $id), true);
            $ids = array();
            if (key($images['images']) === 'id') {
                foreach ($images['images'] as $file) {
                    array_push($ids, $file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $subcategory->delete();

            return response()->json([
                "message" => " زیر شاخه با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    private function generateUrl($url, $title)
    {
        return $url ? str_replace(' ', '-', $url) : str_replace(' ', '-', $title);
    }
}
