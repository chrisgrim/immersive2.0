<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\Conversation;
use App\Models\Event;
use App\Models\Message;
use App\Models\User;
use App\Models\MakeImage;
use DB;
use Swift_SmtpTransport;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\OrganizerStoreRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUser;
use App\Mail\ContactOrganizer;
use Redirect;
use Illuminate\Support\Str;

class OrganizerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except(['show', 'message','fetchEvents']);
        $this->middleware('can:update,organizer')->except(['store','show','create', 'message', 'fetchEvents']);
    }

    /**
     * Show the form for creating a new resource and Eager load organizer with event and get organizers Check if user has auth
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        $event->load('organizer');
        return view('create.organizer',compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     Check if user has auth
     Create a new organizer model and store details.
     Create details for image naming then store it
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizerStoreRequest $request)
    {  
        $organizer = Organizer::Create($request->except(['image', 'user_id']) + ['user_id' => auth()->id(), 'slug' => Str::slug($request->name)]);
        $request->image ? MakeImage::saveImage($request, $organizer, 600, 600, 'organizer') : null;
        $organizer->update(['status' => 'r']);
    }

    /**
     * Load the show page with the organizer
     *
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function show(Organizer $organizer)
    {
        if($organizer->status !== 'p') { return redirect('/');}
        return view('organizers.show', compact('organizer'));
    }

    /**
     * Fetches the organizers events for the show page
     *
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function fetchEvents(Organizer $organizer, Request $request) 
    {
        return Event::where('status', 'p')->where('organizer_id', $organizer->id)->orderByRaw('closingDate >= NOW() desc')->paginate(9);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function edit(Organizer $organizer)
    {
        return view('organizers.edit', compact('organizer'));
    }

    /**
     * Update the specified resource in storage. 
        Update event with the correct organization.
        First Check to make sure user can edit the organizer. If not, then redirect back with error.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizerStoreRequest $request, Organizer $organizer)
    {
        // $web = get_headers($request->website, 1)[0];
        // if (strstr($web, '302') || strstr($web, '200 OK') || strstr($web, '301')) {
        // } else { return abort(404, "broken");}
        if ($request->name !== $organizer->name && $request->image)   MakeImage::renameImage($organizer, null, 'organizer', $request);

        $organizer = Organizer::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'website' => $request->website,
                'email' => $request->email,
                'twitterHandle' => $request->twitterHandle,
                'facebookHandle' => $request->facebookHandle,
                'instagramHandle' => $request->instagramHandle,
                'patreon' => $request->patreon,
            ]
        );
        $request->image ? MakeImage::saveImage($request, $organizer, 600, 600, 'organizer') : null;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organizer $organizer)
    {
        if (auth()->user()->isAdmin()) { 
            return $organizer->deleteOrganizer($organizer); 
        }
        $organizer->events->first->exists() ? null :  $organizer->deleteOrganizer($organizer);
    }

    /**
     * Assign organizer to user.
     *
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function assign(Organizer $organizer)
    {
        auth()->user()->update([
            'current_team_id' => $organizer->id
        ]);
    }

    /**
     * organizer messaging
     *
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function message(Request $request, Organizer $organizer, User $user)
    {   
        $ids = [$organizer->user->id, $user->id];

        $conversation = Conversation::all()->whereIn('user_one', $ids)->whereIn('user_two', $ids)->whereNull('event_id')->first();

        if ($conversation) {
            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'message' => $request->message
            ]);

        } else {
            if( $user->id == $organizer->user->id ) {return false;};
            $ids = [$user->id, $organizer->user->id];
            $conversation = Conversation::create(['user_one' => $user->id, 'user_two' => $organizer->user->id,]);
            $conversation->users()->sync($ids);
            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'message' => $request->message
            ]);
        }



        $conversation->touch();

        $attributes = [
            'email' => $user->email,
            'body' => $request->message,
            'organizer-name' => $organizer->name,
            'name' => $user->name,
        ];

        if($organizer->email) {
            $dest = $organizer->email;
        } else {
            $dest = $organizer->user->email;
        };

        if ($organizer->user->silence == 'n') {
            Mail::mailer('smtp')->to($dest)->send(new ContactOrganizer($attributes));
        }
        if ($user->silence == 'n') {
            Mail::mailer('smtp')->to($user->email)->send(new ContactUser($attributes));
        }

        $organizer->user->update(['unread' => 'm']);

    }
}
