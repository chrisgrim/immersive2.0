<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curated\Community;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\Curated\CommunityComments;
use App\Mail\Curated\CommunityApproved;
use App\Mail\Curated\CommunityRejected;
use App\Models\ImageFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommunityController extends Controller
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
        $communities = Community::where('status', 'r')->paginate(20);
        return view('adminArea.communities', compact('communities'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function show(Community $community)
    {
        $shelves = $community->shelves()->with('publicPostsWithCards')->get();
        $community->load('curators');
        return view('adminArea.showcommunityapproval', compact('community', 'shelves'));
    }

    /**
     * Approve Event 
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function approve(Community $community)
    {
        $community->update([ 'status' => 'p' ]);

        if( !$community->curators->contains('id', auth()->id()) ) {
            foreach ($community->curators as $curator) {
                Mail::to($curator->email)->send(new CommunityApproved($community));
            }
        }

    }

    /**
     * Rejects the event without letting the user have the ability to tweak
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, Community $community)
    {
        if( !$community->curators->contains('id', auth()->id()) ) {
            foreach ($community->curators as $curator) {
                Mail::to($curator->email)->send(new CommunityRejected($request->message, $community));
            }
        }

        ImageFile::deletePreviousImages($community);
        $community->delete();
    }

    /**
     * Respond to Reqest
     */
    public function respondRequest(Request $request, EventRequest $EventRequest)
    {
        Message::eventnotification($EventRequest->event, $request->textarea, null);
        $EventRequest->update([ 'status' => 'p' ]);
    }
}
