<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewEvent;
use Illuminate\Http\Request;

class ReviewEventsController extends Controller
{
    /**
     * Checks for admin before allowing controller access
     *
     * @return \Illuminate\Http\__construct
     */
    public function __construct()
    {
        $this->middleware('moderator')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReviewEvent::orderBy('created_at', 'desc')->with('event')->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('adminArea.createreview');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ReviewEvent::saveReviewEvent($request);
        return ReviewEvent::orderBy('created_at', 'desc')->with('event')->paginate(10);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EventReview  $eventReview
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReviewEvent $reviewevent)
    {
        $reviewevent->update($request->all());
        return ReviewEvent::orderBy('created_at', 'desc')->with('event')->paginate(10);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EventReview  $eventReview
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReviewEvent $reviewevent)
    {
        $reviewevent->delete();
        return ReviewEvent::orderBy('created_at', 'desc')->with('event')->paginate(10);
    }
}
