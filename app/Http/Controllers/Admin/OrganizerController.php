<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organizer;
use Illuminate\Http\Request;

class OrganizerController extends Controller
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
        return view('adminArea.organizers');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        return Organizer::with('user', 'users')->paginate(30);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Organizer $organizer)
    {
        foreach ($organizer->events as $event) {
            $event->update([
                'user_id' => $request->id,
            ]);
        }
        $organizer->update([
            'user_id' => $request->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organizer $organizer)
    {
        if($request->user_id){
            foreach($organizer->events()->get() as $event) {
                $event->update([
                    'user_id' => $request->user_id,
                ]);
            };
        };
        $organizer->update($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request, Organizer $organizer)
    {
        foreach ($organizer->events as $event) {
            $event->update([ 'user_id' => $request->id ]);
        }
        $organizer->update([ 'user_id' => $request->id ]);
    }

    /**
     * index of organizations to be approved
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\view
     */
    public function queues()
    {
        $organizers = Organizer::where('status', 'r')->get();
        return view('adminArea.orgapproval',compact('organizers'));
    }

    /**
     * Finalization of specific organizations approval
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function finalize(Organizer $organizer)
    {
        $organizer = $organizer->load('user');
        return view('adminArea.showorgapproval',compact('organizer'));
    }

    /**
     * Add a team member to the organization
     *
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function addTeamMember(Organizer $organizer, Request $request)
    {

        $ids = collect($request)->pluck('id');
        $data = [];
        foreach($ids as $id) { 
            $data[$id] = [ 'role' => 'moderator' ];
        }
        $organizer->users()->sync($data, ['role' => 'moderator']);
    }

     /**
     * Approve Organization
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function approve(Organizer $organizer)
    {
        $organizer->update(['status' => 'p']);
    }

     /**
     * Delete Organization
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function delete(Organizer $organizer)
    {
        if ($organizer->events) {
            foreach ($organizer->events as $event) {
                $event->delete();
            }
        }
        $organizer->delete();
    }

}
