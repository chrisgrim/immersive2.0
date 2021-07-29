<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\Curated\Listing;
use App\Models\Curated\Community;
use App\Models\Curated\Card;
use App\Models\ImageFile;
use Illuminate\Support\Str;
use App\Http\Requests\ListingStoreRequest;
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
        if($community->status !== 'p') { return redirect('/communities');}
        $this->authorize('update', $community);
        $shelves = $community->shelves()->with('listingsWithCards')->get();
        
        return view('communities.listings.index', compact('community', 'shelves'));
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
    public function store(ListingStoreRequest $request, Community $community)
    {
        $this->authorize('update', $community);

        $listing = $community->listings()->create([
            'blurb' => $request->blurb,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $community->id,
            'shelf_id' => $request->shelf_id,
            'user_id' => auth()->user()->id,
        ]);

        if ($request->image) { ImageFile::saveImage($request, $listing, 1000, 600, 'listing'); }

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
        $this->authorize('preview', $listing);

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
        $this->authorize('edit', $listing);

        $listing->load('cards', 'user');
        return view('communities.listings.edit', compact('listing','community'));
    }

    /**
     * Fetch the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function fetch(Listing $listing)
    {
        return $listing->load('cards','user');
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
    public function update(ListingStoreRequest $request, Community $community, Listing $listing)
    {
        $this->authorize('update', $listing);

        $listing->update($request->except(['image']));
        $listing->update(['slug' => Str::slug($request->name) . '-' . $community->id]);

        if ($request->image) { ImageFile::replaceImage($request, $listing, 1000, 600, 'listing'); }

        if ($request->deleteImage) { 
            ImageFile::deletePreviousImages($listing); 
            ImageFile::clearImagePaths($listing); 
        }
        
        return $listing->load('cards', 'user');
    }

    /**
     * Order the specified resource.
     *
     * @param  \App\Curated\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        foreach ($request->all() as $list) {
            Listing::find($list['id'])->update([
                'order' => $list['order'],
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
        return $listing->shelf->load('listingsWithCards');
    }
}
