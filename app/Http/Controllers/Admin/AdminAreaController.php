<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Organizer;
use App\Models\Curated\Community;
use App\Models\Events\EventRequest;

class AdminAreaController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usercount = User::count();
        $eventcount = Event::where('status', 'p')->count();
        return view('adminArea.stats', compact('usercount', 'eventcount'));
    }

    /**
     * Checking to see what request are left to go over
     *
     * @return \Illuminate\Http\Response
     */
    public function nav() {
        return [
            'event' => Event::where('status', 'r')->count(),
            'org' => Organizer::where('status', 'r')->count(),
            'com' => Community::where('status', 'r')->count(),
            'request' => EventRequest::where('status', 'r')->count()
        ];
    }

    
    
}
