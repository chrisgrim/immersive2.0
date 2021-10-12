<?php

namespace App\Actions\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Post;
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
        return $community->shelves()->orderBy('status', 'DESC')->orderBy('order', 'DESC')->get()->map(function ($shelf, $key) {
            return $shelf->setRelation('posts', $shelf->posts()->paginate(4));
        });
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
        return $shelf->setRelation('posts', $shelf->posts()->paginate(4));
    }

    /**
     * Destroys an existing Shelf
     *
     * @param  array  $input
     * @return \App\Models\Curated\Shelf
     */
    public function destroy(Shelf $shelf)
    {
        $shelf->delete();
        return $shelf->community->shelves()->limit(3)->get()->map(function ($shelf, $key) {
            return $shelf->setRelation('posts', $shelf->posts()->paginate(4));
        });
    }

    /**
     * Reorders an existing shelf
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