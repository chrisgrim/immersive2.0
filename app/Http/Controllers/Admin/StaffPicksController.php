<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffPick;
use App\Models\Event;
use App\Models\User;
use App\Models\EventSearchRule;
use App\Http\Requests\StaffPickRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffPicksController extends Controller
{
    /**
    * Checks for admin before allowing controller access
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('moderator')->except('fetch', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        return $request->user ? StaffPick::where('user_id', $request->user)->paginate(10) : StaffPick::paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staff = User::whereIn('type', ['a', 'm'])->get();
        // $staff = StaffPick::staffWithPicks();
        return view('adminArea.staffpicks', compact('staff'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        StaffPick::saveStaffPick($request);
        return StaffPick::paginate(10);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\StaffPick  $staffPick
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (Carbon::now()->startOfWeek()->addDays(3) >= Carbon::now()) {
            $thursday = Carbon::now()->startOfWeek()->subDays(4);
            $wednesday = Carbon::now()->startOfWeek()->addDays(2);
        } else {
            $thursday = Carbon::now()->startOfWeek()->addDays(3);
            $wednesday = Carbon::now()->startOfWeek()->addDays(9);
        }

        $week = $thursday->format('D') . ' ' .  $thursday->format('d') . ' to ' . $wednesday->format('D') . ' ' .  $wednesday->format('d');

        $staffpicks = StaffPick::where(function($query) use ($thursday, $wednesday){
                $query->whereDate('start_date', '>=', $thursday)
                        ->whereDate('end_date', '<=', $wednesday);
            })
            ->orderBy('rank')
            ->get();
        return view('staffpicks.show', compact('staffpicks', 'week'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StaffPick  $staffPick
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffPick $staffpick)
    {
        $staffpick->update([
            'rank' => $request->rank,
            'comments' => $request->comments,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        return StaffPick::paginate(10);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaffPick  $staffPick
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffPick $staffpick)
    {
        if ($staffpick->event->rank > 3) {
            $staffpick->event->decrement('rank',3);
        }
        $staffpick->delete();
        return StaffPick::paginate(10);
    }
}
