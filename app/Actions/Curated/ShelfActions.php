<?php

namespace App\Actions\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Listing;
use App\Models\Curated\Shelf;
use App\Models\Curated\Community;
use Illuminate\Support\Str;

class ShelfActions
{

    /**
     * Create a newly registered shelf.
     *
     * @param  array  $input
     * @return \App\Models\Curated\Shelf
     */
    public function create(Request $request, Community $community)
    {
        $community->shelves()->create(['user_id' => auth()->id()]);
        return $community->shelves()->with('listingsWithCards')->get();
    }

    /**
     * Updates an existing shelf
     *
     * @param  array  $input
     * @return \App\Models\Curated\Shelf
     */
    public function update(Request $request, Shelf $shelf)
    {
        $shelf->update(['name' => $request->name]);
        return $shelf->load('listingsWithCards');
    }

    /**
     * Destroys an existing Shelf
     *
     * @param  array  $input
     * @return \App\Models\Curated\Listing
     */
    public function destroy(Shelf $shelf)
    {
        $shelf->delete();
        return $shelf->community->shelves()->with('listingsWithCards')->get();
    }

    /**
     * Destroys an existing shelf
     *
     * @param  array  $input
     * @return \App\Models\Curated\Shelf
     */
    public function reorder(Request $request)
    {
        foreach ($request->all() as $list) {
            Shelf::find($list['id'])->update([
                'order' => $list['order'],
            ]);
        }
    }
}