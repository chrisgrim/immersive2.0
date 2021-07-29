<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\User;
use App\Models\Curated\Collection;
use App\Models\Curated\Community;
use App\Models\Curated\Listing;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\CommunityStoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CommunityController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $communities = auth()->user()->communities->load('limitedListings');
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
    public function store(CommunityStoreRequest $request)
    {
        $community = Community::create([
            'blurb' => $request->blurb,
            'name' => $request->name,
            'user_id' => auth()->id(),
            'slug' => Str::slug($request->name),
        ]);

        $community->shelves()->create(['name' => 'First Shelf']);

        if ($request->image) { ImageFile::saveImage($request, $community, 1500, 800, 'community'); }

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
        if($community->status !== 'p') { return redirect('/communities');}
        $shelves = $community->shelves()->with('publicListingsWithCards')->limit(3)->get();
        $community->load('curators');
        return view('communities.show', compact('community', 'shelves'));
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
    public function update(CommunityStoreRequest $request, Community $community)
    {
        $this->authorize('update', $community);

        $community->update($request->except(['image']));

        if ($request->image) { ImageFile::replaceImage($request, $community, 1500, 800, 'community'); }

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

    /**
     * Returns user back to homepage with a submitted popup
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function submitted()
    {
        return redirect('/communities')->with('submitted', 'Your community has been submitted for review');
    }

    /**
     * Makes a new owner of the community
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function updateOwner(Request $request, Community $community)
    {
        $this->authorize('owner', $community);
        $community->update([
            'user_id' => $request->id
        ]);
        return $community->fresh()->load('curators', 'owner');
    }

    /**
     * Adds a Curator to the Community
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function addCurator(Request $request, Community $community)
    {
        $this->authorize('owner', $community);
        $curator =  User::where('email', '=', $request->email)->first();
        if ($curator) {
            $community->curators()->attach($curator->id);
            return $community->fresh()->load('curators', 'owner');
        } else {
            throw ValidationException::withMessages(['user' => 'No User exists with that email']);
        }
    }

    /**
     * Removes Curator from Community
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function removeCurator(Request $request, Community $community)
    {
        $this->authorize('owner', $community);
        $community->curators()->detach($request->id);
        return $community->fresh()->load('curators', 'owner');
    }


}
