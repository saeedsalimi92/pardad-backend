<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Slider;
use Illuminate\Http\Request;
use Validator;
class SliderController extends Controller
{
    public function index()
    {

        $slider = Slider::orderBy('created_at', 'desc')->paginate(15);
        foreach ($slider as $item){
            $image = (new ImagesController)->showByParent('slider' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($slider);
    }
    public function list()
    {
        $slider = Slider::all();
        $data = [];

        foreach ($slider as $item){

            $data[] = (object) [
                'label' => $item->title,
                'value' => $item->id
            ];

        }
        return response()->json($data);
    }
    public function singleList(Request $request)
    {
        $slider = Blog::where('parent_id', $request->id)->get();

        $data = [];

        foreach ($slider as $item){

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



        $slider = new Slider;
        $slider->title = $request->title;
        $slider->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $slider->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $slider->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $slider->description = is_null($request->description) ? '' : $request->description;
        $slider->en_description = is_null($request->en_description) ? '' : $request->en_description;
        $slider->theme = is_null($request->theme) ? '' : $request->theme;
        $slider->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
        $slider->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
        $slider->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
        $slider->priority = is_null($request->priority) ? '' : $request->priority;

        $slider->images =  json_encode($data);
        $slider->status = is_null($request->status) ? 0 : $request->status;
        $slider->canonical = is_null($request->canonical) ? '' : $request->canonical;
        $slider->no_index = is_null($request->no_index) ? '' : $request->no_index;

        $slider->save();
        $id = $slider->latest()->first()->id;
        if($files = $request->file('image')){
            foreach($files as $file){
                $path = (new ImagesController)->store($file , 'images' ,'slider' , $id);
                $data['images'][] = $path->original;
            }
        }
        $slider->images = json_encode($data);

        $slider->save();
        return response()->json([
            "message" => "اسلایدر با موفقیت اضافه شد."
        ], 201);

    }

    public function show($id)
    {
        $slider = Slider::find($id);
        if(!empty($slider))
        {
            $image = (new ImagesController)->showByParent('slider' , $id);
            $slider->images = json_decode($image);
            return response()->json($slider);
        }
        else
        {
            return response()->json([
                "message" => "اسلایدر یافت نشد"
            ], 404);
        }
    }




    public function update(Request $request, $id)
    {
        if (Slider::where('id', $id)->exists()) {
            $slider = Slider::find($id);
            $slider->title = is_null($request->title) ? $slider->title : $request->title;
            $slider->en_title = is_null($request->en_title) ? $slider->en_title : $request->en_title;
            $slider->en_subtitle = is_null($request->en_subtitle) ? $slider->en_subtitle : $request->en_subtitle;
            $slider->subtitle = is_null($request->subtitle) ? $slider->subtitle : $request->subtitle;
            $slider->en_description = is_null($request->en_description) ? $slider->en_description : $request->en_description;
            $slider->description = is_null($request->description) ? $slider->description : $request->description;
            $slider->status = is_null($request->status) ? $slider->status : $request->status;
            $slider->theme = is_null($request->theme) ? $slider->theme : $request->theme;
            $slider->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $slider->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $slider->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $slider->priority = is_null($request->priority) ? '' : $request->priority;
            $slider->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $slider->no_index = is_null($request->no_index) ? '' : $request->no_index;
            $slider->save();
            return response()->json([
                "message" => "اسلایدر با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => "اسلایدر یافت نشد."
            ], 404);
        }
    }
    public function updateImages(Request $request, $id)
    {
        if (Slider::where('id', $id)->exists()) {
            $slider = Slider::find($id);
            $slider->images = (new ImagesController)->showByParent('slider' , $id);
            $data = [

                'images'=>json_decode($slider->images)->images,
            ];
            $image = json_decode(($slider->images),true);

            if($request->has_images == "true"){

                if($files = $request->file('images')){
                    foreach($files as $file){


                        $path = (new ImagesController)->store($file , 'images','slider' , $id);
                        foreach($image as $key => $value) {
                            if($key == 'images') {
                                array_push($image['images'],$path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }



            $slider->images = $data;
            $slider->save();
            return response()->json([
                "message" => "اسلایدر با موفقیت بروز رسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => "اسلایدر یافت نشد."
            ], 404);
        }
    }
    public function deleteImage(Request $request , $slider , $id)
    {

        if (Slider::where('id' ,$slider)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        }else{
            return response()->json([
                "message" => "اسلایدر یافت نشد."
            ], 404);
        }
    }
    public function destroy($id)
    {
        if(Slider::where('id', $id)->exists()) {
            $slider = Slider::find($id);
            $images =json_decode((new ImagesController)->showByParent('slider' , $id) , true) ;
            $ids= array();
            if(key($images['images'] ) === 'id'){
                foreach($images['images'] as $file){
                    array_push($ids ,$file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $slider->delete();

            return response()->json([
                "message" => "اسلایدر با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => "اسلایدر یافت نشد."
            ], 404);
        }
    }
}
