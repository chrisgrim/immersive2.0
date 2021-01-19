<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

use Illuminate\Http\Request;

class BoneyardController extends Controller
{
    /**
     * Checks for admin before allowing controller access
     *
     * @return \Illuminate\Http\__construct
     */
    public function __construct()
    {
        $this->middleware('moderator');
    }

    /**
     * Display a listing of the resource in Boneyard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adminArea.boneyard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        return Event::onlyTrashed()
                ->whereNotNull('name')
                ->orderByDESC('created_at')
                ->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resurrect($id)
    {
        $event = Event::withTrashed()->find($id);
        $event->restore();
        $event = $event->fresh();
        $event->searchable();
        return Event::onlyTrashed()->whereNotNull('name')->orderByDESC('created_at')->paginate(10);
    }

}
