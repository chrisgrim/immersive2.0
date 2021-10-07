<?php

namespace App\Actions\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Post;
use App\Models\Curated\Community;
use Illuminate\Support\Str;
use App\Models\User;

class CommunityActions
{

    /**
     * Create a newly registered community.
     *
     * @param  array  $input
     * @return \App\Models\Curated\Community
     */
    public function create(Request $request)
    {
        $community = Community::create([
            'blurb' => $request->blurb,
            'description' => $request->description ? $request->description : null,
            'name' => $request->name,
            'user_id' => auth()->id(),
            'slug' => Str::slug($request->name),
        ]);
        if ($request->image) { ImageFile::saveImage($request, $community, 800, 500, 'community'); }
        $community->shelves()->create(['user_id' => auth()->id()]);
        $community->shelves()->create(['user_id' => auth()->id(), 'name' => 'Archived', 'status' => 'a']);
        $community->curators()->attach(auth()->user()->id);
        return $community; 
    }

    /**
     * Updates an existing community
     *
     * @param  array  $input
     * @return \App\Models\Curated\Community
     */
    public function update(Request $request, Community $community)
    {
        $community->update($request->except(['image']));
        if ($request->image) { ImageFile::replaceImage($request, $community, 800, 500, 'community'); }
        return $community->load('curators');
    }

    /**
     * Destroys an existing community
     *
     * @param  array  $input
     * @return \App\Models\Curated\Community
     */
    public function destroy(Community $community)
    {
        ImageFile::deletePreviousImages($community);
        $community->delete();
        return auth()->user()->communities;
    }

    /**
     * Adds a curator to a community
     *
     * @param  array  $input
     * @return \App\Models\Curated\Community
     */
    public function addCurator(Request $request, Community $community)
    {
        $curator =  User::where('email', '=', $request->email)->first();
        if ($curator) {
            $community->curators()->attach($curator->id);
            return $community->fresh()->load('curators', 'owner');
        } else {
            throw ValidationException::withMessages(['user' => 'No User exists with that email']);
        }
    }

    /**
     * Removes the curator of a community
     *
     * @param  array  $input
     * @return \App\Models\Curated\Community
     */
    public function removeCurator(Request $request, Community $community)
    {
        $community->curators()->detach($request->id);
        return $community->fresh()->load('curators', 'owner');
    }

    /**
     * Updates the owner of a community
     *
     * @param  array  $input
     * @return \App\Models\Curated\Community
     */
    public function updateOwner(Request $request, Community $community)
    {
        $community->update([ 'user_id' => $request->id ]);
        return $community->fresh()->load('curators', 'owner');
    }

}
