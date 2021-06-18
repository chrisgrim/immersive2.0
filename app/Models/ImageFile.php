<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ImageFile extends Model
{
    use HasFactory;

    public static function saveImage($request, $collection, $width, $height, $type)
    {
        $name = ImageFile::generateName($collection);
        $fileName = ImageFile::generateFileName($request, $name);
        ImageFile::storeImage($request, $collection, $name, $fileName, $width, $height, $type);
        ImageFile::updateCollection($collection, $name, $type);
    }

    public static function generateName($collection)
    {
        return Str::slug($collection->name ? $collection->name : $collection->id);
    }

    public static function generateFileName($request, $name)
    {
        $ext = $request->file('image')->getClientOriginalExtension();
        return $name . '.' . $ext;
    }

    public static function storeImage($request, $collection, $name, $fileName, $width, $height, $type)
    {
        $request->file('image')->storeAs("/public/$type-images/$name-$collection->id", $fileName);
        Image::make(storage_path()."/app/public/$type-images/$name-$collection->id/$fileName")
        ->orientate()
        ->fit($width, $height )
        ->save(storage_path("/app/public/$type-images/$name-$collection->id/$name.webp"))
        ->save(storage_path("/app/public/$type-images/$name-$collection->id/$name.jpg"))
        ->fit( $width / 2, $height / 2 )
        ->save(storage_path("/app/public/$type-images/$name-$collection->id/$name-thumb.webp"))
        ->save(storage_path("/app/public/$type-images/$name-$collection->id/$name-thumb.jpg"));
    }

    public static function updateCollection($collection, $name, $type)
    {
        $collection->update([ 
            'largeImagePath' => "$type-images/$name-$collection->id/$name.webp",
            'thumbImagePath' => "$type-images/$name-$collection->id/$name-thumb.webp",
        ]);
    }
}
