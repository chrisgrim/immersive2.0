<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchData extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = ['search_term','search_type'];

    /**
     * Stores the users search based on category or tag
     *
     * @return nothing is returned
     */
    public static function store($request)
    {
        SearchData::create([
            'search_term' => $request->name,
            'search_type' => $request->type
        ]);
    }
}
