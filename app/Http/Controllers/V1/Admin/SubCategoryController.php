<?php

namespace App\Http\Controllers\V1\Admin;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Validator;

class SubCategoryController extends Controller
{
    public function index()
    {
        $perPage = request()->input('per_page', 15);

        $subcategory = SubCategory::orderBy('created_at', 'desc')->paginate($perPage);


        return response()->json($subcategory);
    }

    public function client_Index()
    {
        $perPage = request()->input('per_page', 15);

        $subcategory = SubCategory::where('status', 'active')
            ->orderBy('created_at', 'desc')->paginate($perPage);


        return response()->json($subcategory);
    }

    public function indexByParent(Request $request)
    {
        $perPage = request()->input('per_page', 15);
        $subcategory = SubCategory::where('parent_id', $request->parent_id)->paginate($perPage);
        foreach ($subcategory as $item) {
            $item->type = 'sub_category';
        }
        return response()->json($subcategory);
    }
    public function client_indexByParent(Request $request)
    {
        $perPage = request()->input('per_page', 15);
        $subcategory = SubCategory::where('parent_id', $request->parent_id)->where('status', 'active')->paginate($perPage);
        foreach ($subcategory as $item) {
            $item->type = 'sub_category';
        }
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
                'type'=>'sub_category',
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
                'type'=>'sub_category',
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
                'type'=>'sub_category',
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
                'type'=>'sub_category',
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


    public function destroy($id)
    {
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);
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
