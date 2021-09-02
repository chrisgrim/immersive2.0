<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InteractiveLevel;
use Illuminate\Http\Request;
use App\Http\Requests\InteractiveLevelStoreRequest;

class InteractiveLevelController extends Controller
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
        return InteractiveLevel::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminArea.interactivelevels');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InteractiveLevelStoreRequest $request)
    {
        InteractiveLevel::saveInteractiveLevel($request);
        return InteractiveLevel::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InteractiveLevel $interactivelevel)
    {
        $interactivelevel->updateInteractiveLevel($request);
        return InteractiveLevel::all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InteractiveLevel $interactivelevel)
    {
        $interactivelevel->delete();
        return InteractiveLevel::all();
    }
}
