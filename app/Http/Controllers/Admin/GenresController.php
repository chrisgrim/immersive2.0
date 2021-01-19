<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
Use App\Models\Genre;
use App\Http\Requests\GenreStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    /**
     * Checks for admin before allowing controller access
     *
     * @return \Illuminate\Http\__construct
     */
    public function __construct()
    {
        $this->middleware('moderator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Genre::orderBy('name')->paginate(40);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminArea.genres');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Genre::saveGenre($request);
        return Genre::orderBy('name')->paginate(40);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $Genre
     * @return \Illuminate\Http\Response
     */
    public function update(GenreStoreRequest $request, Genre $genre)
    {
        $genre->updateGenre($request);
        return Genre::orderBy('name')->paginate(40);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $Genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return Genre::orderBy('name')->paginate(40);
    }
}
