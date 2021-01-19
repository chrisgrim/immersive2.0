<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RemoteLocation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RemoteLocationsController extends Controller
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
        return RemoteLocation::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminArea.remotelocations');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        RemoteLocation::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'admin' => true,
            'user_id' => auth()->user()->id
        ]);
        return RemoteLocation::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RemoteLocation $remotelocation)
    {
        $remotelocation->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'description' => $request->description,
            'rank' => $request->rank,
            'admin' => $request->admin,
        ]);
        return RemoteLocation::all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RemoteLocation $remotelocation)
    {
        $remotelocation->delete();
        return RemoteLocation::all();
    }
}
