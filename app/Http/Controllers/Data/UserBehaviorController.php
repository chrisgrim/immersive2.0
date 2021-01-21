<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\TrackClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserBehaviorController extends Controller
{

    public function trackClicks(Request $request)
    {
        TrackClick::create([
            'event_id' => $request->event,
            'organizer_id' => $request->organizer,
            'user_id' => Auth::id()
        ]);
    }

}