<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Logo;


use Nette\Utils\Random;

use Validator;

class LogoController extends Controller
{
    public function index()
    {
        $logo = Logo::orderBy('created_at', 'desc')->paginate(45);
        return response()->json($logo);
    }


    public function store(Request $request)
    {



        $file = $request->file('image')[0];

        // Get filename with the extension
        $filenameWithExt = $file->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $file->getClientOriginalExtension();

        // Filename to store
        $fileNameToStore = time() . '_' . Random::generate(15) . '_' . $filename . '.' . $extension;
        // Upload Image
        $path = $file->storeAs('images', $fileNameToStore, ['disk' => 'my_upload']);

        $path = str_replace('images/', 'storage/images/', $path);
        if (!$path) {
            return false;
        }

        $logo = new Logo();

        $logo->name = $fileNameToStore;
        $logo->src = env('SITE_URL') . $path;
        $logo->status = is_null($request->title) ? 0 : 1;
        $logo->title = is_null($request->title) ? '' : $request->title ;
        $logo->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $logo->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $logo->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $logo->description = is_null($request->description) ? '' : $request->description;
        $logo->en_description = is_null($request->en_description) ? '' : $request->en_description;
        $logo->save();

        return response()->json([
            "message" => " لوگو با موفقیت اضافه شد."
        ], 201);


    }

    public function show($id)
    {
        $logo = Logo::find($id);
        if(!empty($logo))
        {
            return response()->json($logo);
        }
        else
        {
            return response()->json([
                "message" => " لوگو یافت نشد"
            ], 404);
        }
    }



    public function destroy($id)
    {
        if(Logo::where('id', $id)->exists()) {
            $logo = Logo::find($id);
            $logo->delete();

            return response()->json([
                "message" => " لوگو با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " لوگو یافت نشد."
            ], 404);
        }
    }
}
