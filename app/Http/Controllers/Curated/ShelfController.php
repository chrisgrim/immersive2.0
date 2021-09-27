<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\Curated\Community;
use App\Models\Curated\Shelf;
use App\Http\Controllers\Controller;
use App\Actions\Curated\ShelfActions;

class ShelfController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Community $community, ShelfActions $shelfActions)
    {
        return $shelfActions->create($request, $community);
    }

    /**
     * update an existing model
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shelf $shelf, ShelfActions $shelfActions)
    {
        return $shelfActions->update($request, $shelf);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shelf $shelf, ShelfActions $shelfActions)
    {
        return $shelfActions->destroy($shelf);
    }

    /**
     * Paginate the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function paginate(Request $request, Shelf $shelf, ShelfActions $shelfActions)
    {
        return $shelf->posts()->paginate(4);
    }

    /**
     * Order the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request, Community $community, ShelfActions $shelfActions)
    {
        return $shelfActions->reorder($request);
    }
}