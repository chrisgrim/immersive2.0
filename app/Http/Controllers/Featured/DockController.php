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
        $docks = Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'DESC')->get();
        return view('adminArea.featured.index', compact('docks'));
    }
    /**
     * Store a section for a community
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $dock = auth()->user()->docks()->create();
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'DESC')->get();
    }

    /**
     * update an existing model
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dock $dock)
    {
        $dock->update($request->all());
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'DESC')->get();
    }

    /**
     * update an existing model with associated models
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addShelf(Request $request, Dock $dock, Shelf $shelf)
    {
        $dock->communities()->detach();
        $dock->posts()->detach();
        $dock->shelves()->detach();
        $shelf->docks()->attach($dock);
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'DESC')->get();
    }

     /**
     * update an existing model with associated models
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCommunity(Request $request, Dock $dock, Community $community)
    {
        $dock->communities()->detach();
        $dock->posts()->detach();
        $dock->shelves()->detach();
        $community->docks()->attach($dock);
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'DESC')->get();
    }

     /**
     * update an existing model with associated models
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPost(Request $request, Dock $dock, Post $post)
    {
        $dock->communities()->detach();
        $dock->posts()->detach();
        $dock->shelves()->detach();
        return $post->docks()->attach($dock);
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'DESC')->get();
    }

    /**
     * Destroys an existing dock
     *
     * @param  array  $input
     * @return \App\Models\Curated\Dock
     */
    public function destroy(Dock $dock)
    {
        $dock->posts()->detach();
        $dock->shelves()->detach();
        $dock->delete();
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'DESC')->get();
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
