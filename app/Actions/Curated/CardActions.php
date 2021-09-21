<?php

namespace App\Actions\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Post;

class CardActions
{

    /**
     * Create a newly registered card.
     *
     * @param  array  $input
     * @return \App\Models\Curated\Card
     */
    public function create(Request $request, Post $post)
    {
        $card = Card::create([
            'blurb' => $request->blurb,
            'name' => $request->name ? $request->name : null,
            'url' => $request->url ? $request->url : null,
            'event_id' => $request->event_id ? $request->event_id : null,
            'post_id' => $post->id,
            'order' => $post->cards()->exists() ? $post->cards->last()->order + 1 : 0
        ]);

        if ($request->thumbImagePath) { $card->update(['thumbImagePath' => $request->thumbImagePath]); }
        if ($request->image) { ImageFile::saveCardImage($request, $card, 800, 500, 'card'); }

        return $post->load('cards', 'user');
    }

    /**
     * Updates an existing card
     *
     * @param  array  $input
     * @return \App\Models\Curated\Card
     */
    public function update(Request $request, Card $card)
    {
        $card->update($request->except(['image']));
        if ($request->image) { ImageFile::replaceCardImage($request, $card, 800, 500, 'card'); }
        return $card;
    }

    /**
     * Destroys an existing card
     *
     * @param  array  $input
     * @return \App\Models\Curated\Card
     */
    public function destroy(Card $card)
    {
        $post = $card->post_id;
        $card->destroyCard($card);
        return Post::with('cards','user')->find($post);
    }

    /**
     * Destroys an existing card
     *
     * @param  array  $input
     * @return \App\Models\Curated\Card
     */
    public function reorder(Request $request)
    {
        foreach ($request->all() as $card) {
            Card::find($card['id'])->update([
                'order' => $card['order'],
            ]);
        }
    }

}
