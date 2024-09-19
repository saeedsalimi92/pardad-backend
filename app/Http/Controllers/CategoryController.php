<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;
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

        foreach ($category as $item){

            $data[] = (object) [
                'label' => $item->title,
                'value' => $item->id
            ];

        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = [];


        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'subtitle' => 'required|min:4',
            'description' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 400);
        }


        $category = new Category;
        $category->title = $request->title;
        $category->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $category->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $category->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $category->description = is_null($request->description) ? '' : $request->description;
        $category->en_description = is_null($request->en_description) ? '' : $request->en_description;
        $category->images = json_encode($data);
        $category->logos = json_encode($data);
        $category->status = is_null($request->status) ? 0 : $request->status;
        $category->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
        $category->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
        $category->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
        $category->canonical = is_null($request->canonical) ? '' : $request->canonical;
        $category->no_index = is_null($request->no_index) ? '' : $request->no_index;

        $category->save();
        $id = $category->latest()->first()->id;
        if ($files = $request->file('image')) {
            foreach ($files as $file) {
                $path = (new ImagesController)->store($file, 'images', 'category', $id);
                $data['images'][] = $path->original;
            }
        }
        $category->images = json_encode($data);

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
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->title = is_null($request->title) ? $category->title : $request->title;
            $category->en_title = is_null($request->en_title) ? $category->en_title : $request->en_title;
            $category->en_subtitle = is_null($request->en_subtitle) ? $category->en_subtitle : $request->en_subtitle;
            $category->subtitle = is_null($request->subtitle) ? $category->subtitle : $request->subtitle;
            $category->en_description = is_null($request->en_description) ? $category->en_description : $request->en_description;
            $category->description = is_null($request->description) ? $category->description : $request->description;
            $category->status = is_null($request->status) ? $category->status : $request->status;

            $category->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $category->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $category->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $category->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $category->no_index = is_null($request->no_index) ? '' : $request->no_index;
            $category->save();
            return response()->json([
                "message" => " دسته بندی با موفقیت بروزرسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " دسته بندی یافت نشد."
            ], 404);
        }
    }

    public function updateLogos(Request $request, $id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);

            Log::info($request->logos);

            $category->Logos = is_null($request->logos) ? Null : $request->logos;


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
