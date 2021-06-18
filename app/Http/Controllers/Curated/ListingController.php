<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\Curated\Listing;
use App\Models\Curated\Community;
use App\Models\MakeImage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class ListingController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Community $community)
    {
        return view('communities.listings.create', compact('community'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listing = Listing::create([
            'blurb' => $request->blurb,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'community_id' => $request->community_id,
            'user_id' => auth()->user()->id,
        ]);

        if ($request->image) { MakeImage::saveImage($request, $listing, 1200, 500, 'listing'); }

        return $listing;
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function show(Community $community, Listing $listing)
    {
        return $listing->largeImagePatha;
        $listing->load('cards');
        return view('communities.listings.show', compact('listing','community'));
    }

    /**
     * Fetch the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function fetch(Listing $listing)
    {
        return $listing->load('cards');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Listing $listing)
    {
        $listing->update($request->except(['image']));

        if ($request->image) { MakeImage::saveNewImage($request, $listing, 1200, 500, 'listing'); }
    }
}
