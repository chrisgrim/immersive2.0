<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\Curated\Card;
use App\Models\Curated\Post;
use App\Http\Requests\CardStoreRequest;
use App\Actions\Curated\CardActions;
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
    public function store(Request $request, Post $post, CardActions $cardActions)
    {
        return $cardActions->create($request, $post);
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
    public function update(CardStoreRequest $request, Card $card, CardActions $cardActions)
    {
        return $cardActions->update($request, $card);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card, CardActions $cardActions)
    {
        return $cardActions->destroy($card);
    }

    /**
     * Order the specified resource.
     *
     * @param  \App\Curated\card  $card
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request, Post $post, CardActions $cardActions)
    {
        $cardActions->reorder($request);
    }
}
