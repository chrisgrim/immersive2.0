<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class MakeImage extends Model
{

    /**
    * Saves an image when the user submits their event for the first time.
    *
    * @return string
    */
    public static function generateImage($request, $item, $width, $height, $type)
    {
        // create either new-titles or random like 69sjj3s
        $name = $item->name ? $item->name : $item->id;

        // generate rand variable like 546ds3g
        $rand = substr(md5(microtime()),rand(0,26),7);

        // create title like: new-titles
        $title = Str::slug($name);

        // get extension: (jpg)
        $extension = $request->file('image')->getClientOriginalExtension();

        // combine title and extension:  new-titles.jpg
        $inputFile= $title . '.' . $extension;

        // create filename and set it = to title: new-titles
        $fileName= $title;

        // create directory: event-images/new-titles-54fwd3g
        $directory= $type . '-images/' . $title . '-' . $rand;

        $jpg = Image::make($request->file('image'))->orientate()->fit( $width, $height )->encode('jpg');
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.webp", $webp);

        $jpg = Image::make($jpg)->fit( $width / 2, $height / 2 );
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.webp", $webp);

        $item->update([ 'thumbImagePath' => $directory . '/' . $fileName. '-thumb.webp' ]);
    }


    /**
    * Saves an image when the user submits their event for the first time.
    *
    * @return string
    */
    public static function saveImage($request, $value, $width, $height, $type)
    {
        // create either new-titles or random like 69sjj3s
        $name = $value->name ? $value->name : substr(md5(microtime()),rand(0,26),7);

        // generate rand variable like 546ds3g
        $rand = substr(md5(microtime()),rand(0,26),7);

        // create title like: new-titles
        $title = Str::slug($name);

        // get extension: (jpg)
        $extension = $request->file('image')->getClientOriginalExtension();

        // combine title and extension:  new-titles.jpg
        $inputFile= $title . '.' . $extension;

        // create filename and set it = to title: new-titles
        $fileName= $title;

        // create directory: event-images/new-titles-54fwd3g
        $directory= $type . '-images/' . $title . '-' . $rand;

        $jpg = Image::make($request->file('image'))->orientate()->fit( $width, $height )->encode('jpg');
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.webp", $webp);

        $jpg = Image::make($jpg)->fit( $width / 2, $height / 2 );
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.webp", $webp);

        $value->update([ 
            'largeImagePath' => $directory . '/' . $fileName. '.webp',
            'thumbImagePath' => $directory . '/' . $fileName. '-thumb.webp',
        ]);
    }

    /**
    * Saves an image after the user has already created and event and is updating.
    *
    * @return nothing
    */
    public static function updateImage($request, $value, $width, $height, $type)
    {
        // Generate random number: 39csx48
        $rand = substr(md5(microtime()),rand(0,26),7);

        // Get file extension: jpg
        $extension = $request->file('image')->getClientOriginalExtension();

        // Generate filename based of slug: myevent
        $imageName = $value->slug;

        // Add extension to slug: myevent.jpg
        $inputFile = $imageName . '.' . $extension;

        // Create variable filename based of Imagename: myevent
        $fileName = $imageName;

        // Create directory name: event-images/myevent-54fwd3g-temp
        $directory = $type .'-images/' . $imageName . '-' . $rand . '-' . 'final';

        $jpg = Image::make($request->file('image'))->orientate()->fit( $width, $height )->encode('jpg');
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.webp", $webp);

        $jpg = Image::make($jpg)->fit( $width / 2, $height / 2 );
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.webp", $webp);

        $value->update([ 
            'largeImagePath' => $directory . '/' . $fileName . '.webp',
            'thumbImagePath' => $directory . '/' . $fileName . '-thumb.webp',
        ]);
    }

    /**
    * Finalizes the Image when the admin approves the event. Correctly names it.
    *
    * @return nothing
    */
    public static function renameImage($value, $slug, $type, $request)
    {
        // Name is = tp slug or the request slug
        $name = $slug ? $slug : $request->slug;

        // Get the directory from the original imagepath: event-images/myevent-e2e9agg
        $directory = pathinfo($value->largeImagePath, PATHINFO_DIRNAME);

        // Get the filename from the original imagepath: myevent
        $fileName = pathinfo($value->largeImagePath, PATHINFO_FILENAME);

        // Generate a random number: 546dsee
        $rand = substr(md5(microtime()),rand(0,26),7);
        // Create a new imagepath: event-images/myevent-546dsee-final/mynewevent
        $newImagePath = $type . '-images/' . $name . '-' . $rand . '-'. 'final' . '-' . '1' . '/' . $name;

        Storage::disk('digitalocean')->copy('public/' . $directory . '/' . $fileName . '.webp', 'public/' . $newImagePath . '.webp' );
        Storage::disk('digitalocean')->copy('public/' . $directory . '/' . $fileName . '.jpg', 'public/' . $newImagePath . '.jpg' );
        Storage::disk('digitalocean')->copy('public/' . $directory . '/' . $fileName . '-thumb.webp', 'public/' . $newImagePath . '-thumb.webp' );
        Storage::disk('digitalocean')->copy('public/' . $directory . '/' . $fileName . '-thumb.jpg', 'public/' . $newImagePath . '-thumb.jpg' );

        $value->update([
            'largeImagePath' => $newImagePath . '.webp',
            'thumbImagePath' => $newImagePath . '-thumb.webp',
        ]);
    }

    /**
    * Saves an image when the user submits their event for the first time.
    *
    * @return string
    */
    public static function saveNewImage($request, $value, $width, $height, $type)
    {
        // Check if pathinfo for the largeimage path exists and is over 7 in length
        if (strlen(pathinfo($value->largeImagePath, PATHINFO_DIRNAME)) > 7) {
            // Get the pathinfo: event-images/myevent-e2e9agg
            $directory = pathinfo($value->largeImagePath, PATHINFO_DIRNAME);
            // Get the filename: myevent
            $fileName = pathinfo($value->largeImagePath, PATHINFO_FILENAME);
            //Delete the original folder
            Storage::disk('digitalocean')->deleteDirectory('public/' . $directory);

        } else {
            // Generate random number: 546ds3g
            $rand = substr(md5(microtime()),rand(0,26),7);
            // Generate random filename: fd45cz3
            $fileName = substr(md5(microtime()),rand(0,26),7);
            // Create dirctory name:  event-images/new-titles-54fwd3g-temp
            $directory = $type . '-images/' . $fileName . '-' . $rand . '-' . 'draft';
        }

        // Get extension: jpg
        $extension = $request->file('image')->getClientOriginalExtension();
        // Create title: new-titles.jpg
        $inputFile = $fileName . '.' . $extension;

        $jpg = Image::make($request->file('image'))->orientate()->fit( $width, $height )->encode('jpg');
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.webp", $webp);

        $jpg = Image::make($jpg)->fit( $width / 2, $height / 2 );
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.webp", $webp);

        $value->update([ 
            'largeImagePath' => $directory . '/' . $fileName. '.webp',
            'thumbImagePath' => $directory . '/' . $fileName. '-thumb.webp',
        ]);
    }

    /**
    * Finalizes the Image when the admin approves the event. Correctly names it.
    *
    * @return nothing
    */
    public static function finalize($value, $slug, $type, $request)
    {   
        // Get the pathinfo: event-images/myevent-e2e9agg
        $directory = pathinfo($value->largeImagePath, PATHINFO_DIRNAME);

        // Get the filename: myevent
        $fileName = pathinfo($value->largeImagePath, PATHINFO_FILENAME);

        // Name is = tp slug or the request slug
        $name = $slug ? $slug : $request->slug;

        // Generate a random number: 546dsee
        $rand = substr(md5(microtime()),rand(0,26),7);
        // Create a new imagepath: event-images/myevent-546dsee-final/mynewevent
        $newImagePath = $type . '-images/' . $name . '-' . $rand . '-'. 'final'  . '/' . $name;

        Storage::disk('digitalocean')->copy('public/' . $directory . '/' . $fileName . '.webp', 'public/' . $newImagePath . '.webp' );
        Storage::disk('digitalocean')->copy('public/' . $directory . '/' . $fileName . '.jpg', 'public/' . $newImagePath . '.jpg' );
        Storage::disk('digitalocean')->copy('public/' . $directory . '/' . $fileName . '-thumb.webp', 'public/' . $newImagePath . '-thumb.webp' );
        Storage::disk('digitalocean')->copy('public/' . $directory . '/' . $fileName . '-thumb.jpg', 'public/' . $newImagePath . '-thumb.jpg' );

        $value->update([
            'largeImagePath' => $newImagePath . '.webp',
            'thumbImagePath' => $newImagePath . '-thumb.webp',
        ]);

        //Delete the original folder
        Storage::disk('digitalocean')->deleteDirectory('public/' . $directory);
    }

    /**
    * Saves an image when the user submits their event for the first time.
    *
    * @return string
    */
    public static function saveUserImage($request, $value, $width, $height, $type)
    {
        // Check if pathinfo for the largeimage path exists and is over 7 in length
        if (strlen(pathinfo($value->largeImagePath, PATHINFO_DIRNAME)) > 7) {
            // Get the pathinfo: event-images/myevent-e2e9agg
            $directory = pathinfo($value->largeImagePath, PATHINFO_DIRNAME);
            // Get the filename: myevent
            $fileName = pathinfo($value->largeImagePath, PATHINFO_FILENAME);
            //Delete the original folder
            Storage::disk('digitalocean')->deleteDirectory('public/' . $directory);

        } else {
            // Generate random number: 546ds3g
            $id = auth()->user()->id;
            // Generate random filename: fd45cz3
            $fileName = auth()->user()->name;
            // Create dirctory name:  event-images/new-titles-54fwd3g-temp
            $directory = $type . '-images/' . $fileName . '-' . $id;
        }

        // Get extension: jpg
        $extension = $request->file('image')->getClientOriginalExtension();
        // Create title: new-titles.jpg
        $inputFile = $fileName . '.' . $extension;

        $jpg = Image::make($request->file('image'))->orientate()->fit( $width, $height )->encode('jpg');
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName.webp", $webp);

        $jpg = Image::make($jpg)->fit( $width / 2, $height / 2 );
        $webp = Image::make($jpg)->encode('webp');
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.jpg", $jpg);
        Storage::disk('digitalocean')->put( "/public/$directory/$fileName-thumb.webp", $webp);

        
        $value->update([ 
            'largeImagePath' => $directory . '/' . $fileName. '.webp',
            'thumbImagePath' => $directory . '/' . $fileName. '-thumb.webp',
        ]);
    }

    /**
    * Saves an image when the user submits their event for the first time.
    *
    * @return string
    */
    public static function deleteImage($item)
    {
        $directory = pathinfo($item->thumbImagePath, PATHINFO_DIRNAME);
        Storage::deleteDirectory('public/' . $directory);
    }

}
