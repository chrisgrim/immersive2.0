<?php

namespace App\Http\Controllers\Admin\Featured;

use Illuminate\Http\Request;
use App\Models\Featured\Dock;
use App\Models\Curated\Community;
use App\Models\Curated\Shelf;
use App\Models\Organizer;
use App\Actions\Admin\Featured\DockActions;
use App\Http\Requests\Admin\DockUpdateRequest;
use App\Http\Controllers\Controller;

class DockController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
        $this->middleware('moderator');
    }

    /**
     * Place to save docks
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docks = Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'ASC')->get();
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
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'ASC')->get();
    }

    /**
     * update an existing model
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(DockUpdateRequest $request, Dock $dock)
    {
        $dock->update($request->all());
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'ASC')->get();
    }

    /**
     * update an existing model with associated models
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addShelf(Dock $dock, Shelf $shelf, DockActions $dockActions)
    {
        $dockActions->addShelf($dock, $shelf);
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'ASC')->get();
    }

     /**
     * update an existing model with associated models
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCommunity(Dock $dock, Community $community, DockActions $dockActions)
    {
        $dockActions->addCommunity($dock, $community);
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'ASC')->get();
    }

     /**
     * update an existing model with associated models
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPost(Dock $dock, Post $post, DockActions $dockActions)
    {
        $dockActions->addPost($dock, $post);
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'ASC')->get();
    }

    /**
     * Destroys an existing dock
     *
     * @param  array  $input
     * @return \App\Models\Curated\Dock
     */
    public function destroy(Dock $dock, DockActions $dockActions)
    {
        $dockActions->destroy($dock);
        return Dock::with(['posts', 'shelves', 'communities'])->orderBy('order', 'ASC')->get();
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
