<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\Curated\Listing;
use App\Models\Curated\Community;
use App\Models\Curated\Card;
use App\Models\ImageFile;
use Illuminate\Support\Str;
use App\Http\Requests\ListingStoreRequest;
use App\Actions\Curated\ListingActions;
use App\Models\Featured\Section;
use App\Models\Featured\Feature;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class ListingController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }

    /**
     * Show the form for editing a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Community $community)
    {
        //
    }

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
    public function store(ListingStoreRequest $request, Community $community, ListingActions $listingActions)
    {
        return $listingActions->create($request, $community);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function show(Community $community, Listing $listing)
    {
        $listing->load('cards', 'user');
        return view('communities.listings.show', compact('listing','community'));
    }

    /**
     * Edit the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function edit(Community $community, Listing $listing)
    {
        $listing->load('cards', 'user', 'section');
        return view('communities.listings.edit', compact('listing','community'));
    }

    /**
     * Pagin ate the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function paginate(Community $community)
    {
        return $community->listings()->latest()->paginate(10);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function update(ListingStoreRequest $request, Community $community, Listing $listing, ListingActions $listingActions)
    {
        return $listingActions->update($request, $listing);
    }

    /**
     * Order the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request, Community $community, ListingActions $listingActions)
    {
        return $listingActions->reorder($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listing $listing, ListingActions $listingActions)
    {
        return $listingActions->destroy($listing);
    }
}
