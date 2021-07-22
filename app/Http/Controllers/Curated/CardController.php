<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Listing;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $card = Card::create([
            'blurb' => $request->blurb,
            'name' => $request->name ? $request->name : null,
            'url' => $request->url ? $request->url : null,
            'listing_id' => $listing->id,
            'order' => $listing->cards()->exists() ? $listing->cards->last()->order + 1 : 0
        ]);

        if ($request->thumbImagePath) { $card->update(['thumbImagePath' => $request->thumbImagePath]); }
        if ($request->image) { ImageFile::saveCardImage($request, $card, 1200, 500, 'card'); }

        return $listing->load('cards', 'user');
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
        $this->authorize('update', $card);

        $card->update($request->except(['image']));

        if ($request->image) { ImageFile::replaceCardImage($request, $card, 1200, 500, 'card'); }

        return $card->fresh();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $this->authorize('delete', $card);

        $listing = $card->listing_id;
        $card->destroyCard($card);
        return Listing::with('cards','user')->find($listing);
    }
}
