<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Validator;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::orderBy('created_at', 'desc')->paginate(15);
        foreach ($contact as $item){
            $image = (new ImagesController)->showByParent('contact' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($contact);
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



        $contact = new Contact;
        $contact->title = $request->title;
        $contact->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $contact->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $contact->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $contact->description = is_null($request->description) ? '' : $request->description;
        $contact->en_description = is_null($request->en_description) ? '' : $request->en_description;
        $contact->images =  json_encode($data);
        $contact->info =  json_encode($data);
        $contact->status = is_null($request->status) ? 0 : $request->status;
        $contact->address = is_null($request->address) ? '' : $request->address;
        $contact->en_address = is_null($request->en_address) ? '' : $request->en_address;
        $contact->address2 = is_null($request->address2) ? '' : $request->address2;
        $contact->en_address2 = is_null($request->en_address2) ? '' : $request->en_address2;
        $contact->phone = is_null($request->phone) ? '' : $request->phone;
        $contact->phone2 = is_null($request->phone2) ? '' : $request->phone2;
        $contact->mobile = is_null($request->mobile) ? '' : $request->mobile;
        $contact->mobile2 = is_null($request->mobile2) ? '' : $request->mobile2;
        $contact->email = is_null($request->email) ? '' : $request->email;
        $contact->email2 = is_null($request->email2) ? '' : $request->email2;
        $contact->theme = is_null($request->theme) ? '' : $request->theme;

        $contact->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
        $contact->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
        $contact->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;

        $contact->save();
        $id = $contact->latest()->first()->id;
        if($files = $request->file('image')){
            foreach($files as $file){
                $path = (new ImagesController)->store($file , 'images' ,'contact' , $id);
                $data['images'][] = $path->original;
            }
        }
        $contact->images = json_encode($data);

        $contact->save();
        return response()->json([
            "message" => " ایتم با موفقیت اضافه شد."
        ], 201);

    }

    public function show($id)
    {
        $contact = Contact::find($id);
        if(!empty($contact))
        {
            $image = (new ImagesController)->showByParent('contact' , $id);
            $contact->images = json_decode($image);
            return response()->json($contact);
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
        if (Contact::where('id', $id)->exists()) {
            $contact = Contact::find($id);
            $contact->title = is_null($request->title) ? $contact->title : $request->title;
            $contact->en_title = is_null($request->en_title) ? $contact->en_title : $request->en_title;
            $contact->en_subtitle = is_null($request->en_subtitle) ? $contact->en_subtitle : $request->en_subtitle;
            $contact->subtitle = is_null($request->subtitle) ? $contact->subtitle : $request->subtitle;
            $contact->en_description = is_null($request->en_description) ? $contact->en_description : $request->en_description;
            $contact->description = is_null($request->description) ? $contact->description : $request->description;
            $contact->status = is_null($request->status) ? $contact->status : $request->status;
            $contact->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $contact->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $contact->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $contact->address = is_null($request->address) ? '' : $request->address;
            $contact->en_address = is_null($request->en_address) ? '' : $request->en_address;
            $contact->address2 = is_null($request->address2) ? '' : $request->address2;
            $contact->email = is_null($request->email) ? '' : $request->email;
            $contact->email2 = is_null($request->email2) ? '' : $request->email2;
            $contact->theme = is_null($request->theme) ? '' : $request->theme;
            $contact->en_address2 = is_null($request->en_address2) ? '' : $request->en_address2;
            $contact->phone = is_null($request->phone) ? '' : $request->phone;
            $contact->phone2 = is_null($request->phone2) ? '' : $request->phone2;
            $contact->mobile = is_null($request->mobile) ? '' : $request->mobile;
            $contact->mobile2 = is_null($request->mobile2) ? '' : $request->mobile2;

            $contact->info = json_encode([]);
            $contact->save();
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
        if (Contact::where('id', $id)->exists()) {
            $contact = Contact::find($id);
            $contact->images = (new ImagesController)->showByParent('contact' , $id);
            $data = [
                'logo'=>json_decode($contact->images)->logo,
                'back_image'=>json_decode($contact->images)->back_image,
                'images'=>json_decode($contact->images)->images,
                'video'=>json_decode($contact->images)->video,
            ];
            $image = json_decode(($contact->images),true);

            if($request->has_images == "true"){

                if($files = $request->file('images')){
                    foreach($files as $file){


                        $path = (new ImagesController)->store($file , 'images','contact' , $id);
                        foreach($image as $key => $value) {
                            if($key == 'images') {
                                array_push($image['images'],$path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }



            $contact->images = $data;
            $contact->save();
            return response()->json([
                "message" => " ایتم با موفقیت بروز رسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " ایتم یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request , $contact , $id)
    {

        if (Contact::where('id' ,$contact)->exists()) {
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
        if(Contact::where('id', $id)->exists()) {
            $contact = Contact::find($id);
            $images =json_decode((new ImagesController)->showByParent('contact' , $id) , true) ;
            $ids= array();
            if(key($images['images'] ) === 'id'){
                foreach($images['images'] as $file){
                    array_push($ids ,$file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $contact->delete();

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
