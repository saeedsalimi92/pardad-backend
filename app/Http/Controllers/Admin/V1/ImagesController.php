<?php

namespace App\Http\Controllers\Admin\V1;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\ImageEditor;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Random;

/**
 * @OA\Tag(
 *     name="Admin Blogs",
 *     description="Admin images operations"
 * )
 */
class ImagesController extends Controller
{
    public function index(Request $request)
    {
        $images = Image::orderBy('created_at', 'desc')->simplePaginate(15);
        /*foreach ($images as $item) {
            $page = Pages::find($item->parent_id);
            $item->page = $page;
        }*/
        return response()->json($images);

    }

    public function pendingImage(Request $request)
    {
        /* $images = Images::orderBy('created_at', 'desc')->paginate(25);
         foreach ($images as $item) {
             if ($item->parent_type == 'category') {
                 $page = Category::find($item->parent_id);
             } else {
                 $page = Pages::find($item->parent_id);
             }
             $item->page = $page;
         }
         return response()->json($images);*/
        switch ($request->query('status')) {
            case 'accepted' :
            case 'pending' :
            case 'rejected' :

                return response()->json($this->geTimages($request));

                break;
            default:
                $images = Image::orderBy('created_at', 'desc')->paginate(25);
                foreach ($images as $item) {
                    if ($item->parent_type == 'category') {
                        $page = Category::find($item->parent_id);
                    } else {
                        $page = Pages::find($item->parent_id);
                    }

                    $item->page = $page;
                }
                return response()->json($images);
        }

    }


    public function show($id)
    {
        $image = Image::find($id);

        if (!empty($image)) {
            /*if ($image->parent_type == 'category') {
                $page = Category::find($image->parent_id);
            } else {
                $page = Pages::find($image->parent_id);
            }
            $image->page = $page;*/
            return response()->json($image);
        } else {
            return response()->json([
                "message" => "عکس یافت نشد"
            ], 404);
        }
    }

    public function showByParent($name, $id)
    {
        $image = Image::where('parent_type', '=', $name)->where('parent_id', '=', $id)->get();


        if (!empty($image)) {
            $test = json_decode($image, true);
            $images = [];
            foreach ($test as $key => $value) {


                if ($value['position'] == 'images') {
                    array_push($images, $value);
                }

            }
            $items = [
                'images' => $images,
            ];
            return json_encode($items, true);
        } else {
            $items = [
                'images' => []
            ];
            return json_encode($items, true);
        }
    }

    public function showMany($ids)
    {

        $image = Image::findMany($ids);

        if (!empty($image)) {
            return response()->json($image);
        } else {
            return response()->json([]);
        }
    }


    public function update(Request $request, $id)
    {
        if (Image::where('id', $id)->exists()) {


            $image = Image::find($id);
            $image->name = is_null($request->name) ? $image->name : $request->name;
            $image->src = is_null($request->src) ? $image->src : $request->src;
            $image->isActive = is_null($request->isActive) ? $image->isActive : $request->isActive;
            $image->status = is_null($request->status) ? $image->status : $request->status;
            $image->type = is_null($request->type) ? $image->type : $request->type;
            $image->position = is_null($request->position) ? $image->position : $request->position;

            $image->save();

            $imageEditor = new ImageEditor();
            $imageEditor->user_id = $image->parent_id;
            $imageEditor->image_id = $id ;
            $imageEditor->save();
            return response()->json([
                "message" => "عکس با موفقیت به روزرسانی شد."
            ], 200);
        } else {
            return response()->json([
                "message" => "عکس یافت نشد"
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (Image::where('id', $id)->exists()) {
            $image = Image::find($id);
            $lng = strlen(env('SITE_URL'));
            $lng = $lng + 15;
            $name = substr($image->src, $lng);
            if (Storage::delete('public/images/' . $name)) {
                $image->delete();
            }
        } else {
            return response()->json([
                "message" => "عکس یافت نشد."
            ], 404);
        }
    }

    public function destroyMany($id)
    {

        if (Image::whereIn('id', $id)->exists()) {
            $images = Image::findMany($id);
            foreach ($images as $image) {
                $lng = strlen(env('SITE_URL'));
                $lng = $lng + 15;
                $name = substr($image->src, $lng);
                if (Storage::delete('public/images/' . $name)) {
                    $image->delete();
                } else {
                    return response()->json([
                        "message" => "مشکلی در پاک کردن عکس پیش آمده بعدا امتحان فرمایید."
                    ], 400);
                }
            }

        } else {
            return response()->json([
                "message" => "عکس یافت نشد."
            ], 404);
        }
    }

    public function store($file, $file_name, $type, $id)
    {



        // Get filename with the extension
        $filenameWithExt = $file->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $file->getClientOriginalExtension();

        // Filename to store
        $fileNameToStore = time() . '_' . Random::generate(15) . '_' . $filename . '.' . $extension;
        // Upload Image
        $path = $file->storeAs('images', $fileNameToStore, ['disk' => 'public']);

        $path = str_replace('images/', 'storage/images/', $path);
        if (!$path) {
            return false;
        }

        $image = new Image();

        $image->name = $fileNameToStore;
        $image->src = ImagesController . phpenv('') . $path;
        $image->isActive = true;
        $image->status = $image->status = 'accepted';
        $image->type = 0;
        $image->position = $file_name;
        $image->parent_type = $type;
        $image->parent_id = $id;
        $image->save();
        $image_id = $image->latest()->first()->id;
        $up_image = $image::find($image_id);
        return response()->json([
            "id" => $up_image->id,
            "src" => $up_image->src,
            "isActive" => $up_image->isActive,
            "status" => $up_image->status,
            "type" => $up_image->type,
            "position" => $up_image->position,
            "parent_type" => $up_image->parent_id,
            "parent_id" => $up_image->parent_type,
            "created_at" => $up_image->created_at,
            "updated_at" => $up_image->updated_at
        ], 200);

    }
    public function storeFile(Request $request)
    {
        $file = $request->file('file');



        // Get filename with the extension
        $filenameWithExt = $file->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $file->getClientOriginalExtension();

        // Filename to store
        $fileNameToStore = time() . '_' . Random::generate(15) . '_' . $filename . '.' . $extension;
        // Upload Image
        $path = $file->storeAs('images', $fileNameToStore, ['disk' => 'public']);

        $path = str_replace('images/', 'storage/images/', $path);
        if (!$path) {
            return false;
        }

        $image = new Image();

        $image->name = $fileNameToStore;
        $image->src = ImagesController . phpenv('') . $path;
        $image->isActive = true;
        $image->status = $image->status = 'accepted';
        $image->type = 0;
        $image->position = '';
        $image->parent_type = '';
        $image->parent_id ='';
        $image->save();
        $image_id = $image->latest()->first()->id;
        $up_image = $image::find($image_id);
        return response()->json([
            "id" => $up_image->id,
            "src" => $up_image->src,
            "isActive" => $up_image->isActive,
            "status" => $up_image->status,
            "type" => $up_image->type,
            "position" => $up_image->position,
            "parent_type" => $up_image->parent_id,
            "parent_id" => $up_image->parent_type,
            "created_at" => $up_image->created_at,
            "updated_at" => $up_image->updated_at
        ], 200);

    }

    public function getImages ($request){
        $images = Image::where('status', $request->query('status'))->orderBy('created_at', 'desc')->paginate(25);
        /*foreach ($images as $item) {
            if ($item->parent_type == 'category') {
                $page = Category::find($item->parent_id);
            } else {
                $page = Pages::find($item->parent_id);
            }

            $item->page = $page;
        }*/
        return $images;

    }
}
