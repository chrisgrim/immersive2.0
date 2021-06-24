<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Collection;
use App\Models\Curated\Community;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CommunityController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(['auth', 'verified'])->except('show');
    // }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $communities = auth()->user()->communities;
        return view('communities.index', compact('communities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('communities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $community = Community::create([
            'blurb' => $request->blurb,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        if ($request->image) { ImageFile::saveImage($request, $community, 1200, 500, 'community'); }

        $community->curators()->attach(auth()->user()->id);

        return $community;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function show(Community $community)
    {
        $listings = $community->listings()->latest()->take(3)->get();
        $community->load('curators');
        return view('communities.show', compact('community', 'listings'));
    }

    /**
     * Fetch the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function fetch(Community $community)
    {
        return $community->load('curators');
    }

    /**
     * Fetch the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function list(Community $community)
    {
        //
    }

    /**
     * Fetch the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function listings(Community $community)
    {
        return 'test';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function edit(Community $community)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Community $community)
    {
        $this->authorize('update', $community);

        $community->update($request->except(['image']));

        if ($request->image) { ImageFile::replaceImage($request, $community, 1200, 500, 'community'); }

        return $community->load('curators');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Community $community
     * @return \Illuminate\Http\Response
     */
    public function destroy(Community $community)
    {
        $this->authorize('delete', $community);

        ImageFile::deletePreviousImages($community);
        $community->delete();
        return auth()->user()->communities;
    }
}
