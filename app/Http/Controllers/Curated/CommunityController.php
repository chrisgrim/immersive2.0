<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\Curated\Community;
use App\Models\Featured\Section;
use App\Models\Featured\Feature;
use App\Models\Curated\Listing;
use App\Models\Event;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\CommunityStoreRequest;
use App\Actions\Curated\CommunityActions;
use App\Http\Controllers\Controller;

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
    public function store(CommunityStoreRequest $request, CommunityActions $communityActions)
    {
        return $communityActions->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function show(Community $community)
    {
        // $sections = $community->sections()->with('publicFeatured.featureable')->get();
        $shelves = $community->shelves()->with('publicListingsWithCards')->limit(3)->get();
        $community->load('curators');
        return view('communities.show', compact('community', 'shelves'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function edit(Community $community)
    {
        $shelves = $community->shelves()->with('listingsWithCards')->get();
        // $sections = $community->sections()->with('featured')->get();
        return view('communities.edit', compact('community', 'shelves'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function update(CommunityStoreRequest $request, Community $community, CommunityActions $communityActions)
    {
        return $communityActions->update($request, $community);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Community $community
     * @return \Illuminate\Http\Response
     */
    public function destroy(Community $community, CommunityActions $communityActions)
    {
        return $communityActions->destroy($community);
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
     * Adds a Curator to the Community
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function addCurator(Request $request, Community $community,  CommunityActions $communityActions)
    {
        return $communityActions->addCurator($request, $community);
    }

    /**
     * Removes Curator from Community
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function removeCurator(Request $request, Community $community, CommunityActions $communityActions)
    {
        return $communityActions->removeCurator($request, $community);
    }

    /**
     * Makes a new owner of the community
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function updateOwner(Request $request, Community $community, CommunityActions $communityActions)
    {
        return $communityActions->updateOwner($request, $community);
    }


}
