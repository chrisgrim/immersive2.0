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
        ImageFile::storeSmallImage($request, $collection, $name, $fileName, $width/2, $height/2, $type);
        ImageFile::storeLargeImage($request, $collection, $name, $fileName, $width, $height, $type);
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

    public static function replaceImage($request, $collection, $width, $height, $type)
    {
        ImageFile::deletePreviousImages($collection);
        $name = ImageFile::generateName($collection);
        $fileName = ImageFile::generateFileName($request, $name);
        ImageFile::storeSmallImage($request, $collection, $name, $fileName, $width/2, $height/2, $type);
        ImageFile::storeLargeImage($request, $collection, $name, $fileName, $width, $height, $type);
        ImageFile::updateLargeImagePath($collection, $name, $type);
        ImageFile::updateThumbImagePath($collection, $name, $type);
    }

    public static function replaceCardImage($request, $collection, $width, $height, $type)
    {
        if ($collection->thumbImagePath) {
            ImageFile::deletePreviousImages($collection);
        }
        $name = ImageFile::generateName($collection);
        $fileName = ImageFile::generateFileName($request, $name);
        ImageFile::storeSmallImage($request, $collection, $name, $fileName, $width/2, $height/2, $type);
        ImageFile::storeLargeImage($request, $collection, $name, $fileName, $width, $height, $type);
        ImageFile::updateLargeImagePath($collection, $name, $type);
        ImageFile::updateThumbImagePath($collection, $name, $type);
    }

    public static function generateName($collection)
    {
        if ($collection->event_id) {
            $event = Event::find($collection->event_id);
            return Str::slug($collection->name ? $collection->name . '-' . $collection->id : $event->name . '-' . $collection->id);
        }
        return Str::slug($collection->name ? $collection->name . '-' . $collection->id : $collection->id);
    }

    public static function generateFileName($request, $name)
    {
        $ext = $request->file('image')->getClientOriginalExtension();
        return $name . '.' . $ext;
    }

    public static function storeLargeImage($request, $collection, $name, $fileName, $width, $height, $type)
    {
        $jpg = Image::make($request->file('image'))->orientate()->fit( $width, $height )->encode('jpg');
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$type-images/$name-$collection->id/$name.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$type-images/$name-$collection->id/$name.webp", $webp);
    }

    public static function storeSmallImage($request, $collection, $name, $fileName, $width, $height, $type)
    {
        $jpg = Image::make($request->file('image'))->orientate()->fit( $width, $height )->encode('jpg');
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$type-images/$name-$collection->id/$name-thumb.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$type-images/$name-$collection->id/$name-thumb.webp", $webp);
    }

    public static function removeLargeImage($collection, $name, $fileName, $type)
    {
        $image_path = "/public/$type-images/$name-$collection->id/$fileName";
        if(Storage::disk('digitalocean')->exists($image_path)) {
            Storage::disk('digitalocean')->delete($image_path);
        }
    }

    public static function deletePreviousImages($collection)
    {
        $directory = pathinfo($collection->thumbImagePath, PATHINFO_DIRNAME);
        if (strlen($directory) > 7) { Storage::disk('digitalocean')->deleteDirectory('public/' . $directory); }
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
