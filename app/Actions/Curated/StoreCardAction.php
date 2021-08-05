<?php

namespace App\Actions\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Listing;

class StoreCardAction 
{

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(Request $request, Listing $listing)
    {
        $card = Card::create([
            'blurb' => $request->blurb,
            'name' => $request->name ? $request->name : null,
            'url' => $request->url ? $request->url : null,
            'listing_id' => $listing->id,
            'order' => $listing->cards()->exists() ? $listing->cards->last()->order + 1 : 0
        ]);
        
        if ($request->thumbImagePath) { $card->update(['thumbImagePath' => $request->thumbImagePath]); }
        if ($request->image) { ImageFile::saveCardImage($request, $card, 1200, 500, 'card'); }
    }

    public function update(Request $request, Card $card)
    {
       $card->update($request->except(['image']));

        if ($request->image) { ImageFile::replaceCardImage($request, $card, 1200, 500, 'card'); }
    }

}
