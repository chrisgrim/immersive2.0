<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\Curated\Listing;
use App\Models\Curated\Community;
use App\Models\Curated\Card;
use App\Models\ImageFile;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class ListingController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Community $community)
    {
        $this->authorize('update', $community);
        
        return view('communities.listings.create', compact('community'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Community $community)
    {
        $this->authorize('update', $community);

        $listing = $community->listings()->create([
            'blurb' => $request->blurb,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'user_id' => auth()->user()->id,
        ]);

        if ($request->image) { ImageFile::saveImage($request, $listing, 1200, 500, 'listing'); }

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
        $this->authorize('update', $listing);

        $listing->update($request->except(['image']));

        if ($request->image) { ImageFile::replaceImage($request, $listing, 1200, 500, 'listing'); }
    }

    /**
     * Order the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request, Listing $listing)
    {
        foreach ($request->all() as $card) {
            Card::find($card['id'])->update([
                'order' => $card['order'],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        ImageFile::deletePreviousImages($listing);
        $listing->delete();
    }
}
