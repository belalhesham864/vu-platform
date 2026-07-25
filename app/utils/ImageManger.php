<?php

namespace App\Utils;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageManger
{
    public static function uploadImage($request, $folder)
    {
       

            $image = $request->file($folder);
            $filename = Str::uuid() . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs($folder, $filename, ['disk' => 'uploads']);
            return $path;
        
    }
    public static function update($request, $model, $folder)
    {
        $imagePath = str_replace(asset('/'), '', $model->image);

        if (File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
        $image = $request->file('image');
        $filename = Str::uuid() . time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs($folder, $filename, ['disk' => 'uploads']);
        return $path;
    }


    public static function delete($model)
    {
        $imagePath = str_replace(asset('/'), '', $model->image);

        if (File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}
