<?php

namespace App\Http\Controllers\Featured;

use Illuminate\Http\Request;
use App\Models\Featured\Dock;
use App\Models\Curated\Community;
use App\Models\Curated\Shelf;
use App\Models\Organizer;
use App\Http\Controllers\Controller;

class DockController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }

    /**
     * Place to save docks
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docks = Dock::all()->load('featured');
        return view('featured.index', compact('docks'));
    }
    /**
     * Store a section for a community
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Dock $dock)
    {
        if ($dock->type === 'c') {
            $community = Community::find($request->id);
            if ($community->featured()->where('featureable_id', $community->id)->exists()) {
                $featured = $community->featured()->where('featureable_id', $community->id)->first();
            } else {
                $featured = $community->featured()->create([
                    'user_id' => auth()->id(),
                    'type' => 'c'
                ]);
            }
        }

        if ($dock->type === 's') {
            $shelf = Shelf::find($request->id);
            if ($shelf->featured()->where('featureable_id', $shelf->id)->exists()) {
                $featured = $shelf->featured()->where('featureable_id', $shelf->id)->first();
            } else {
                $featured = $shelf->featured()->create([
                    'user_id' => auth()->id(),
                    'type' => 's'
                ]);
            }
        }

        $dock->featured()->detach();
        $featured->docks()->attach($dock->id);
        return Dock::all()->load('featured');
    }

    /**
     * update an existing model
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $section->update(['name' => $request->name]);
        return $section;
    }

    /**
     * Destroys an existing dock
     *
     * @param  array  $input
     * @return \App\Models\Curated\Dock
     */
    public function destroy(Dock $dock)
    {
        //
    }

    /**
     * Update the order for Docks
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request, Community $community)
    {
        //
    }

}
