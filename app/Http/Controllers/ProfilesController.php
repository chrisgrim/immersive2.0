<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MakeImage;
use Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class ProfilesController extends Controller
{
    /**
    * Checks for admin before allowing controller access
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }

    public function index(User $user)
    {
        //
    }

    public function show(User $user)
    {
        $user
        ->load('location', 'favouritedEvents')
        ->makeHidden(['newsletter_type', 'type', 'hasMessages', 'hasCreatedOrganizers', 'current_team_id', 'card_brand', 'card_last_four', 'email', 'stripe_id']);
        return view('profiles.show', compact('user'));
    }

    public function account()
    {
        return view('profiles.account');
    }

    public function notifications()
    {
        return view('profiles.notifications');
    }

    /**
     * Store a newly created resource in storage. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
    	if($request->image) {
            MakeImage::saveUserImage($request,$user, 400, 400, 'user');
    	}
        if ($request->name) {
            $user->update([ 'name' => $request->name ]);
        }
        if ($request->email) {
            $user->update([ 'email' => $request->email ]);
        }
        
        if ($request->monthly && $request->updates) {
            $user->update([ 'newsletter_type' => 'a' ]);
        } elseif ($request->monthly) {
            $user->update([ 'newsletter_type' => 'm' ]);
        } elseif ($request->updates) {
            $user->update([ 'newsletter_type' => 'u' ]);
        } else {
            $user->update([ 'newsletter_type' => 'n' ]);
        }
        
        if ($request->newsletter) {
            if ($user->newsletter_type == 'n') {
                $user->update([ 'newsletter_type' => 'u' ]);
            }
            if ($user->newsletter_type == 'm') {
                $user->update([ 'newsletter_type' => 'a' ]);
            }
        }
        
        if ($request->messages) {
            $user->update([ 'silence' => 'n' ]);
        } else {
            $user->update([ 'silence' => 'y' ]);
        }

        
        if ($request->location) {
            $user->location()->updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'home' => $request->location['home'],
                    'postal_code' => $request->location['postal_code'],
                    'street' => $request->location['street'],
                    'region' => $request->location['region'],
                    'city' => $request->location['city'],
                    'country' => $request->location['country'],
                    'latitude' => $request->location['latitude'],
                    'longitude' => $request->location['longitude']
                ]
            );
        }
        $user->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('update', $user);
        $user->conversations()->detach();
        $user->delete();
    }

    public function storeUserGeolocation(User $user)
    {
    	Cache::put('key', 'value', 10);
    }

}
