<?php

namespace App\Http\Controllers\Curated;

use Illuminate\Http\Request;
use App\Models\Curated\Community;
use App\Models\Curated\Shelf;
use App\Http\Controllers\Controller;

class ShelfController extends Controller
{
    
    /**
     * return the model
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Shelf $shelf)
    {
        return $shelf->load('listingsWithCards');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Community $community)
    {
        $community->shelves()->create(['name' => 'Shelf']);
        return $community->shelves()->with('listingsWithCards')->get();
    }

    /**
     * update an existing model
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shelf $shelf)
    {
        $shelf->update(['name' => $request->name]);
        return $shelf->load('listingsWithCards');
    }

    /**
     * Order the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        foreach ($request->all() as $list) {
            Shelf::find($list['id'])->update([
                'order' => $list['order'],
            ]);
        }
    }

    /**
     * Destroy the specified resource.
     *
     * @param  \App\Curated\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shelf $shelf)
    {
        $community = $shelf->community;
        $shelf->delete();
        return $community->shelves()->with('listingsWithCards')->get();
    }

}
