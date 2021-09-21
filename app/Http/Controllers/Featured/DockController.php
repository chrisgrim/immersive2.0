<?php

namespace App\Http\Controllers\Featured;

use Illuminate\Http\Request;
use App\Models\Featured\Dock;
use App\Models\Curated\Community;
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
        // Need to create different ones of these for different type of models
        $community = Community::find($request->id);
        if ($community->featured()->where('featureable_id', $community->id)->exists()) {
            $featured = $community->featured()->where('featureable_id', $community->id)->first();
        } else {
            $featured = $community->featured()->create([
                'user_id' => auth()->id(),
                'type' => 'c'
            ]);
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
     * Destroys an existing section
     *
     * @param  array  $input
     * @return \App\Models\Curated\Listing
     */
    public function destroy(Section $section)
    {
        if (count($section->featured)) { return; }
        $section->delete();
    }

    /**
     * Update the order for sections
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request, Community $community)
    {
        foreach ($request->all() as $list) {
            Section::find($list['id'])->update([
                'order' => $list['order'],
            ]);
        }
    }

    /**
     * Update the order for sections
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if ($request->keywords) {
            $val = Community::multiMatchSearch()
                ->fields(['name'])
                ->query($request->keywords)
                ->type('bool_prefix')
                ->size(6)
                ->execute();
            return $val->matches();
        }
        $val = Community::matchAllSearch()
        ->size(6)
        ->execute();
        return $val->matches();
    }
}
