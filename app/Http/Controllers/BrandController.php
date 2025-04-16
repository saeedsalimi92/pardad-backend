<?php

namespace App\Http\Controllers;


use App\Models\Brand;

use Illuminate\Http\Request;

use Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('per_page', 15);
        $brand = Brand::orderBy('created_at', 'desc')->paginate($perPage);
        foreach ($brand as $item){
            $image = (new ImagesController)->showByParent('brand' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($brand);
    }
    public function client_Index()
    {
        $perPage = request()->input('per_page', 6);
        $brand = Brand::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage );
        foreach ($brand as $item){
            $image = (new ImagesController)->showByParent('brand' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($brand);
    }
    public function store(Request $request)
    {

        $messages = [
            'title.required' => 'تیتر الزامی است.',
            'title.min' => 'تیتر باید حداقل 3 کاراکتر باشد.',
            'file.required' => 'تصویر الزامی است.',
            'pdf_file.required' => 'pdf الزامی است.',

        ];

        // اعتبارسنجی فیلدهای الزامی
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'file' => 'required',
            'pdf_file' => 'required',
        ], $messages);

        // اگر اعتبارسنجی با خطا مواجه شد
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 422);
        }


        $brand = new Brand;
        $brand->title = $request->title;
        $brand->description = $request->description ?? '';
        $brand->status = $request->status ?? 'inactive';
        $brand->meta_description = $request->meta_description ?? '';
        $brand->meta_title = $request->meta_title ?? '';
        $brand->meta_keywords = $request->meta_keywords ?? '';
        $brand->canonical = $request->canonical ?? '';
        $brand->no_index = $request->no_index ?? 'inactive';
        $brand->file = $request->file;
        $brand->pdf_file = $request->pdf_file;

        $brand->save();




        return response()->json([
            "message" => " برند با موفقیت اضافه شد."
        ], 201);

    }

    public function show($id)
    {
        $brand = Brand::find($id);
        if(!empty($brand))
        {
            return response()->json($brand);
        }
        else
        {
            return response()->json([
                "message" => " برند یافت نشد"
            ], 404);
        }
    }
    public function client_show($id)
    {
        $brand = Brand::find($id);
        if (empty($brand) || $brand->status === 'inactive') {
            return response()->json([
                "message" => "برند یافت نشد یا غیرفعال است"
            ], 404);
        }
        $image = (new ImagesController)->showByParent('brand' , $id);
        $brand->image = json_decode($image);




        return response()->json($brand);
    }
    public function update(Request $request, $id)
    {
        if (Brand::where('id', $id)->exists()) {
            $brand = Brand::find($id);

            $brand->title = $request->has('title') ? (!empty($request->title) ? $request->title : '') : ($brand->title ?? '');
            $brand->description = $request->has('description') ? (!empty($request->description) ? $request->description : '') : $brand->description;
            $brand->meta_description = $request->has('meta_description') ? (!empty($request->meta_description) ? $request->meta_description : '') : $brand->meta_description;
            $brand->meta_title = $request->has('meta_title') ? (!empty($request->meta_title) ? $request->meta_title : '') : $brand->meta_title;
            $brand->meta_keywords = $request->has('meta_keywords') ? (!empty($request->meta_keywords) ? $request->meta_keywords : '') : $brand->meta_keywords;
            $brand->canonical = $request->has('canonical') ? (!empty($request->canonical) ? $request->canonical : '') : $brand->canonical;
            $brand->no_index = $request->has('no_index') ? (!empty($request->no_index) ? $request->no_index : 'inactive') : $brand->no_index;
            $brand->status = $request->has('status') ? (!empty($request->status) ? $request->status : 'inactive') : $brand->status;
            $brand->file = $request->has('file') ? (!empty($request->file) ? $request->file : '') : $brand->file;
            $brand->pdf_file = $request->has('pdf_file') ? (!empty($request->pdf_file) ? $request->pdf_file : '') : $brand->pdf_file;




            $brand->save();
            return response()->json([
                "message" => " برند با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " برند یافت نشد."
            ], 404);
        }
    }
    public function destroy($id)
    {
        if(Brand::where('id', $id)->exists()) {
            $brand = Brand::find($id);

            $brand->delete();

            return response()->json([
                "message" => " برند با موفقیت حدف شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " برند یافت نشد."
            ], 404);
        }
    }
}
