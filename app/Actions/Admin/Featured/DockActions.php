<?php

namespace App\Actions\Admin\Featured;

use Illuminate\Http\Request;

class DockActions
{

    /**
     * Create a newly registered card.
     *
     * @param  array  $input
     * @return \App\Models\Curated\Card
     */
    public function addShelf($dock, $shelf)
    {
        $dock->communities()->detach();
        $dock->posts()->detach();
        $dock->shelves()->detach();
        $shelf->docks()->attach($dock);
    }

    /**
     * Create a newly registered card.
     *
     * @param  array  $input
     * @return \App\Models\Curated\Card
     */
    public function addCommunity($dock, $community)
    {
        $dock->communities()->detach();
        $dock->posts()->detach();
        $dock->shelves()->detach();
        $community->docks()->attach($dock);
    }

    /**
     * Create a newly registered card.
     *
     * @param  array  $input
     * @return \App\Models\Curated\Card
     */
    public function addPost($dock, $post)
    {
        $dock->communities()->detach();
        $dock->posts()->detach();
        $dock->shelves()->detach();
        $post->docks()->attach($dock);
    }

    /**
     * Destroys an existing card
     *
     * @param  array  $input
     * @return \App\Models\Curated\Card
     */
    public function destroy($dock)
    {
        $dock->posts()->detach();
        $dock->shelves()->detach();
        $dock->communities()->detach();
        $dock->delete();
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
