<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ImageFile extends Model
{
    use HasFactory;

    public static function saveImage($request, $collection, $width, $height, $type)
    {
        $name = ImageFile::generateName($collection);
        $fileName = ImageFile::generateFileName($request, $name);
        ImageFile::storeLargeImage($request, $collection, $name, $fileName, $width, $height, $type);
        ImageFile::storeSmallImage($request, $collection, $name, $fileName, $width/2, $height/2, $type);
        ImageFile::updateLargeImagePath($collection, $name, $type);
        ImageFile::updateThumbImagePath($collection, $name, $type);
    }

    public static function replaceImage($request, $collection, $width, $height, $type)
    {
        ImageFile::deletePreviousImages($collection);
        $name = ImageFile::generateName($collection);
        $fileName = ImageFile::generateFileName($request, $name);
        ImageFile::storeLargeImage($request, $collection, $name, $fileName, $width, $height, $type);
        ImageFile::storeSmallImage($request, $collection, $name, $fileName, $width/2, $height/2, $type);
        ImageFile::updateLargeImagePath($collection, $name, $type);
        ImageFile::updateThumbImagePath($collection, $name, $type);
    }

    public static function saveCardImage($request, $collection, $width, $height, $type)
    {
        $name = ImageFile::generateName($collection);
        $fileName = ImageFile::generateFileName($request, $name);
        ImageFile::storeSmallImage($request, $collection, $name, $fileName, $width, $height, $type);
        ImageFile::updateThumbImagePath($collection, $name, $type);
        ImageFile::removeLargeImage($collection, $name, $fileName, $type);
    }

    public static function replaceCardImage($request, $collection, $width, $height, $type)
    {
        if ($collection->thumbImagePath) {
            if (!Str::contains($collection->thumbImagePath, 'event-images')) { 
                ImageFile::deletePreviousImages($collection);
            }
        }
        $name = ImageFile::generateName($collection);
        $fileName = ImageFile::generateFileName($request, $name);
        ImageFile::storeSmallImage($request, $collection, $name, $fileName, $width, $height, $type);
        ImageFile::updateThumbImagePath($collection, $name, $type);
        ImageFile::removeLargeImage($collection, $name, $fileName, $type);
    }

    public static function generateName($collection)
    {
        return Str::slug($collection->name ? $collection->name . '-' . $collection->id : $collection->id);
    }

    public static function generateFileName($request, $name)
    {
        $ext = $request->file('image')->getClientOriginalExtension();
        return $name . '.' . $ext;
    }

    public static function storeLargeImage($request, $collection, $name, $fileName, $width, $height, $type)
    {
        $request->file('image')->storeAs("/public/$type-images/$name-$collection->id", $fileName);
        Image::make(storage_path()."/app/public/$type-images/$name-$collection->id/$fileName")
        ->orientate()
        ->fit($width, $height )
        ->save(storage_path("/app/public/$type-images/$name-$collection->id/$name.webp"))
        ->save(storage_path("/app/public/$type-images/$name-$collection->id/$name.jpg"));
    }

    public static function storeSmallImage($request, $collection, $name, $fileName, $width, $height, $type)
    {
        $request->file('image')->storeAs("/public/$type-images/$name-$collection->id", $fileName);
        Image::make(storage_path()."/app/public/$type-images/$name-$collection->id/$fileName")
        ->orientate()
        ->fit( $width, $height )
        ->save(storage_path("/app/public/$type-images/$name-$collection->id/$name-thumb.webp"))
        ->save(storage_path("/app/public/$type-images/$name-$collection->id/$name-thumb.jpg"));
    }

    public static function removeLargeImage($collection, $name, $fileName, $type)
    {
        $image_path = "/public/$type-images/$name-$collection->id/$fileName";
        if(Storage::exists($image_path)) {
            Storage::delete($image_path);
        }
    }

    public static function deletePreviousImages($collection)
    {
        $directory = pathinfo($collection->thumbImagePath, PATHINFO_DIRNAME);
        if (strlen($directory) > 7) { Storage::deleteDirectory('public/' . $directory); }
    }

    public static function updateLargeImagePath($collection, $name, $type)
    {
        $collection->update([ 'largeImagePath' => "$type-images/$name-$collection->id/$name.webp" ]);
    }

    public static function updateThumbImagePath($collection, $name, $type)
    {
        $collection->update([ 'thumbImagePath' => "$type-images/$name-$collection->id/$name-thumb.webp" ]);
    }

    public static function clearImagePaths($collection)
    {
        $collection->update([ 
            'thumbImagePath' => null,
            'largeImagePath' => null
        ]);
    }
}