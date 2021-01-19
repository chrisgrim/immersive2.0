<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class PurgatoryController extends Controller
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
     * Display a listing of the resource in Purgatory.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adminArea.purgatory');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        return Event::where('status', '!=', 'p')
                ->where('status', '!=', 'e')
                ->whereNotNull('name')
                ->orderByDESC('created_at')
                ->paginate(10);
    }


}
