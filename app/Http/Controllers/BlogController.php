<?php

namespace App\Http\Controllers;

use App\Models\Blog;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blog::orderBy('created_at', 'desc')->paginate(15);
        foreach ($blog as $item){
            $image = (new ImagesController)->showByParent('blog' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($blog);
    }
    public function client_Index()
    {
        $blog = Blog::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        foreach ($blog as $item){
            $image = (new ImagesController)->showByParent('blog' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($blog);
    }
    public function indexByParent(Request $request)
    {
        $blog = Blog::where('parent_id', $request->parent_id)->orderBy('created_at', 'desc')->paginate(15);
        foreach ($blog as $item){
            $image = (new ImagesController)->showByParent('blog' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($blog);
    }
    public function client_indexByParent(Request $request)
    {
        $blog = Blog::where('parent_id', $request->parent_id)->where('status', 'active')->orderBy('created_at', 'desc')->paginate(15);
        foreach ($blog as $item){
            $image = (new ImagesController)->showByParent('blog' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($blog);
    }
    public function list()
    {

        $category = Blog::all();
        $data = [];

        foreach ($category as $item){

            $data[] = (object) [
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

        $category = Blog::where('status', 'active')->get();
        $data = [];

        foreach ($category as $item){

            $data[] = (object) [
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

        $category = Blog::where('parent_id', $request->parent_id)->where('status', 'active')->get();


        $data = [];

        foreach ($category as $item){

            $data[] = (object) [
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

        $blog = Blog::where('parent_id', $request->parent_id)->where('status', 'active')->get();


        $data = [];

        foreach ($blog as $item){

            $data[] = (object) [
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

        $messages = [
            'title.required' => 'عنوان الزامی است.',
            'title.min' => 'عنوان باید حداقل ۴ کاراکتر باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۴ کاراکتر باشد.',
            'parent_id.required' => 'زیرشاخه الزامی است.',

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






        $subCategory = SubCategory::find($request->parent_id);
        if (!empty($subCategory)) {
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
            $blog->images =  json_encode($image);
            $blog->logos =  json_encode($image);
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
        }else{
            return response()->json(['error' => 'شناسه ریز شاخه یافت نشد.'], 422);
        }

    }

    public function show($id)
    {
        $blog = Blog::find($id);
        if(!empty($blog))
        {
            $data =array();
            $image = (new ImagesController)->showByParent('blog' , $id);
            $blog->images = json_decode($image);



            $blog->images = json_decode($image);
            $blog->logos = is_null($blog->logos) ? Null: json_decode($blog->logos);


            foreach ($blog->logos as $item){
                $logos = (new LogoController)->show($item);
                array_push($data , $logos->original);
            }


            $blog->logos_image = $data;

            return response()->json($blog);
        }
        else
        {
            return response()->json([
                "message" => " بلاگ یافت نشد"
            ], 404);
        }
    }
    public function client_show($id)
    {
        $blog = Blog::find($id);
        if (empty($blog) || $blog->status === 'inactive') {
            return response()->json([
                "message" => "بلاگ یافت نشد یا غیرفعال است"
            ], 404);
        }
        $data =array();
        $image = (new ImagesController)->showByParent('blog' , $id);
        $blog->images = json_decode($image);



        $blog->images = json_decode($image);
        $blog->logos = is_null($blog->logos) ? Null: json_decode($blog->logos);


        foreach ($blog->logos as $item){
            $logos = (new LogoController)->show($item);
            array_push($data , $logos->original);
        }


        $blog->logos_image = $data;

        return response()->json($blog);
    }

    public function update(Request $request, $id)
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
            $blog->telegram = $request->has('telegram') ? (!empty($request->telegram) ? $request->telegram : '') : $blog->telegram;
            $blog->instagram = $request->has('instagram') ? (!empty($request->instagram) ? $request->instagram : '') : $blog->instagram;
            $blog->whatsapp = $request->has('whatsapp') ? (!empty($request->whatsapp) ? $request->whatsapp : '') : $blog->whatsapp;
            $blog->author = $request->has('author') ? (!empty($request->author) ? $request->author : '') : $blog->author;





            $blog->save();
            return response()->json([
                "message" => " بلاگ با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }
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
        }else{
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {
        if (Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);
            $blog->images = (new ImagesController)->showByParent('blog' , $id);
            $data = [
                'images'=>json_decode($blog->images)->images,
            ];
            $image = json_decode(($blog->images),true);

            if($request->has_images == "true"){

                if($files = $request->file('images')){
                    foreach($files as $file){


                        $path = (new ImagesController)->store($file , 'images','blog' , $id);
                        foreach($image as $key => $value) {
                            if($key == 'images') {
                                array_push($image['images'],$path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }



            $blog->images = $data;
            $blog->save();
            return response()->json([
                "message" => " بلاگ با موفقیت بروز رسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request , $blog , $id)
    {

        if (Blog::where('id' ,$blog)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }

    public function destroy($id)
    {
        if(Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);
            $images =json_decode((new ImagesController)->showByParent('blog' , $id) , true) ;
            $ids= array();
            if(key($images['images'] ) === 'id'){
                foreach($images['images'] as $file){
                    array_push($ids ,$file['id']);
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
    }

    private function generateUrl($url, $title)
    {
        return $url ? str_replace(' ', '-', $url) : str_replace(' ', '-', $title);
    }
}
