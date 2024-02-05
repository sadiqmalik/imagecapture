<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Storage;

class ImageController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $img = $request->image;
        $constituencyName = $request->input('constituencyname');
        $folderPath = "uploads/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = strtoupper($constituencyName) . '_' . rand(100, 999999) . '.png';

        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);

        return 'Image uploaded successfully: '. $fileName . ' - ' . $constituencyName;
    }
}
