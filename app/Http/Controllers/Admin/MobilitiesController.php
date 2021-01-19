<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobilityAdvisory;
use Illuminate\Http\Request;

class MobilitiesController extends Controller
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
        return MobilityAdvisory::where('admin', true)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminArea.mobilities');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        MobilityAdvisory::saveMobilitiesLevel($request);
        return MobilityAdvisory::where('admin', true)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $Mobility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MobilityAdvisory $mobility)
    {
        $mobility->updateMobilitiesLevel($request);
        return MobilityAdvisory::where('admin', true)->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $Genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(MobilityAdvisory $mobility)
    {
        $mobility->delete();
        return MobilityAdvisory::where('admin', true)->get();
    }
}
