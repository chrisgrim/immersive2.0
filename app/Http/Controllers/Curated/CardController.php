<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\MakeImage;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Listing;
use App\Http\Controllers\Controller;

class CardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $card = Card::create([
            'blurb' => $request->blurb,
            'name' => $request->name ? $request->name : null,
            'url' => $request->url ? $request->url : null,
            'thumbImagePath' => $request->thumbImagePath ? $request->thumbImagePath : null,
            'listing_id' => $request->listing_id,
        ]);

        if ($request->image) { 
            $directory = ImageFile::saveImage($request, $card, 600, 400, 'card'); 
        }

        return Listing::with('cards')->find($request->listing_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Curated\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        return $card;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        $card->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $listing = $card->listing_id;
        $card->destroyCard($card);
        return Listing::with('cards')->find($listing);
    }
}
