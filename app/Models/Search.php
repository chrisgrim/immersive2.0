<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    /**
     * Stores the users search based on category or tag
     *
     * @return nothing is returned
     */
    public static function convertUrl($request)
    {
        if ($request->price0 || $request->price1) {
            $request->request->add(['price' => [$request->price0, $request->price1]]);
        }
        if ($request->price && $request->price[1] >= 100) {
            $low = $request->price[0];
            $request->request->add(['price' => [$low, 9999]]);
        }
        if (!is_array($request->category) && !empty($request->category)) { $request->request->add(['category' => explode(",",$request->category)]); }
        if (!is_array($request->tag) && !empty($request->tag)) { $request->request->add(['tag' => explode(",",$request->tag)]); }
        if ($request->start) {$request->request->add(['dates' => [$request->start, $request->end]]);}
        return $request;
    }
}
