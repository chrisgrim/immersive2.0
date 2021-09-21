<?php

namespace App\Actions\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Listing;
use App\Models\Curated\Community;
use App\Models\Featured\Feature;
use Illuminate\Support\Str;

class ListingActions
{

    /**
     * Create a newly registered Listing.
     *
     * @param  array  $input
     * @return \App\Models\Curated\Listing
     */
    public function create(Request $request, Community $community)
    {
        $listing = $community->listings()->create([
            'blurb' => $request->blurb,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $community->id,
            'user_id' => auth()->user()->id,
            'shelf_id' => $request->shelf_id,
        ]);

        // $featured = $listing->featured()->create([
        //     'user_id' => auth()->id(),
        //     'type' => 'l'
        // ]);

        // $featured->sections()->attach($request->section_id);

        if ($request->image) { ImageFile::saveImage($request, $listing, 800, 500, 'listing'); }

        return $listing;
    }

    /**
     * Updates an existing listing
     *
     * @param  array  $input
     * @return \App\Models\Curated\Listing
     */
    public function update(Request $request, Listing $listing)
    {
        // if ($listing->section_id !== $request->section_id) {
        //     $listing->featured()->first()->sections()->detach($listing->section_id);
        //     $listing->featured()->first()->sections()->attach($request->section_id);
        // }

        $listing->update($request->except(['image']));
        $listing->update(['slug' => Str::slug($request->name) . '-' . $listing->community->id]);

        if ($request->image) { ImageFile::replaceImage($request, $listing, 800, 500, 'listing'); }

        if ($request->deleteImage) { 
            ImageFile::deletePreviousImages($listing); 
            ImageFile::clearImagePaths($listing); 
        }
        
        return $listing->load('cards', 'user');
    }

    /**
     * Destroys an existing listing
     *
     * @param  array  $input
     * @return \App\Models\Curated\Listing
     */
    public function destroy(Listing $listing)
    {
        ImageFile::deletePreviousImages($listing);
        $listing->delete();
        return $listing->shelf->load('listingsWithCards');
    }

    /**
     * Destroys an existing listing
     *
     * @param  array  $input
     * @return \App\Models\Curated\Listing
     */
    public function reorder(Request $request)
    {
        foreach ($request->all() as $list) {
            Listing::find($list['id'])->update([
                'order' => $list['order'],
            ]);
        }
    }

}
