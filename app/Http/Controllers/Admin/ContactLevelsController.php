<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLevel;
use Illuminate\Http\Request;

class ContactLevelsController extends Controller
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
        return ContactLevel::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminArea.contactlevels');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ContactLevel::saveContactLevel($request);
        return ContactLevel::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContactLevel  $contactLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactLevel $contactlevel)
    {
        $contactlevel->updateContactLevel($request);
        return ContactLevel::all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContactLevel  $contactLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactLevel $contactlevel)
    {
        $contactlevel->delete();
        return ContactLevel::all();
    }
}
