<?php

namespace App\Http\Controllers;

use App\Models\about;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    //
    public function index()
    {
        $about = About::orderBy('created_at', 'desc')->paginate(15);
        foreach ($about as $item){
            $image = (new ImagesController)->showByParent('about' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($about);
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



        $about = new About;
        $about->title = $request->title;
        $about->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $about->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $about->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $about->description = is_null($request->description) ? '' : $request->description;
        $about->en_description = is_null($request->en_description) ? '' : $request->en_description;
        $about->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
        $about->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
        $about->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
        $about->canonical = is_null($request->canonical) ? '' : $request->canonical;
        $about->no_index = is_null($request->no_index) ? '' : $request->no_index;

        $about->images =  json_encode($data);
        $about->status = is_null($request->status) ? 0 : $request->status;

        $about->save();
        $id = $about->latest()->first()->id;
        if($files = $request->file('image')){
            foreach($files as $file){
                $path = (new ImagesController)->store($file , 'images' ,'about' , $id);
                $data['images'][] = $path->original;
            }
        }
        $about->images = json_encode($data);

        $about->save();
        return response()->json([
            "message" => " ایتم با موفقیت اضافه شد."
        ], 201);

    }

    public function show($id)
    {
        $about = About::find($id);
        if(!empty($about))
        {
            $image = (new ImagesController)->showByParent('about' , $id);
            $about->images = json_decode($image);
            return response()->json($about);
        }
        else
        {
            return response()->json([
                "message" => " ایتم یافت نشد"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (About::where('id', $id)->exists()) {
            $about = About::find($id);
            $about->title = is_null($request->title) ? $about->title : $request->title;
            $about->en_title = is_null($request->en_title) ? $about->en_title : $request->en_title;
            $about->en_subtitle = is_null($request->en_subtitle) ? $about->en_subtitle : $request->en_subtitle;
            $about->subtitle = is_null($request->subtitle) ? $about->subtitle : $request->subtitle;
            $about->en_description = is_null($request->en_description) ? $about->en_description : $request->en_description;
            $about->description = is_null($request->description) ? $about->description : $request->description;
            $about->status = is_null($request->status) ? $about->status : $request->status;
            $about->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $about->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $about->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $about->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $about->no_index = is_null($request->no_index) ? '' : $request->no_index;
            $about->save();
            return response()->json([
                "message" => " ایتم با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " ایتم یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {
        if (About::where('id', $id)->exists()) {
            $about = About::find($id);
            $about->images = (new ImagesController)->showByParent('about' , $id);
            $data = [
                'logo'=>json_decode($about->images)->logo,
                'back_image'=>json_decode($about->images)->back_image,
                'images'=>json_decode($about->images)->images,
                'video'=>json_decode($about->images)->video,
            ];
            $image = json_decode(($about->images),true);

            if($request->has_images == "true"){

                if($files = $request->file('images')){
                    foreach($files as $file){


                        $path = (new ImagesController)->store($file , 'images','about' , $id);
                        foreach($image as $key => $value) {
                            if($key == 'images') {
                                array_push($image['images'],$path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }



            $about->images = $data;
            $about->save();
            return response()->json([
                "message" => " ایتم با موفقیت بروز رسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " ایتم یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request , $about , $id)
    {

        if (About::where('id' ,$about)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " ایتم یافت نشد."
            ], 404);
        }
    }

    public function destroy($id)
    {
        if(About::where('id', $id)->exists()) {
            $about = About::find($id);
            $images =json_decode((new ImagesController)->showByParent('about' , $id) , true) ;
            $ids= array();
            if(key($images['images'] ) === 'id'){
                foreach($images['images'] as $file){
                    array_push($ids ,$file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $about->delete();

            return response()->json([
                "message" => " ایتم با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " ایتم یافت نشد."
            ], 404);
        }
    }
}
