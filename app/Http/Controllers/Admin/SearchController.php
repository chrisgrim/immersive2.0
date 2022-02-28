<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\Genre;
use App\Models\User;
use App\Models\Curated\Community;
use Illuminate\Support\Arr;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Return list of Organizers.
     *
     * @return \Illuminate\Http\Response
     */
    public function organizers(Request $request)
    {
        if (! $request->keywords) return Organizer::with('user')->paginate(30);

        $organizer = Organizer::multiMatchSearch()
        ->fields(['name', 'name._2gram','name._3gram'])
        ->query($request->keywords)
        ->type('bool_prefix')
        ->load(['user', 'users'])
        ->paginate(30);

        $filter = tap($organizer->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return  json_encode($filter);
    }

    /**
     * Returned filtered Genres.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function genres(Request $request)
    {
        if (! $request->keywords) return Genre::orderBy('name')->paginate(40);

        $genre = Genre::where('name', 'like', '%' . $request->keywords . '%')
            ->orderBy('rank', 'desc')
            ->paginate(10);

        return  json_encode($genre);
    }

     /**
     * Returned filtered Events.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function events(Request $request)
    {
        if (! $request->keywords) return Event::where('status','p')->with('user','clicks','category', 'location', 'remotelocations', 'shows')->paginate(10);

        $events = Event::multiMatchSearch()
            ->fields(['name', 'name._2gram','name._3gram'])
            ->query($request->keywords)
            ->type('bool_prefix')
            ->sort('rank', 'desc')
            ->load(['user','clicks','category', 'location', 'remotelocations', 'organizer', 'shows'])
            ->paginate(10);

        $filter = tap($events->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($filter);
    }

    /**
     * Return User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request)
    {
        if (! $request->keywords) return User::paginate(10);

        $users = User::multiMatchSearch()
            ->fields(['name', 'email'])
            ->query($request->keywords)
            ->type('bool_prefix')
            ->paginate(10);

        $filter = tap($users->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($filter);
    }

    /**
     * Return User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function communities(Request $request)
    {
        if (! $request->keywords) return Community::with('curators', 'owner')->paginate(10);
        return Community::where('name', 'LIKE', "%$request->keywords%")->with('curators', 'owner')->paginate(10);
    }

     /**
     * Returned filtered Events.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function purgatory(Request $request)
    {
        if (! $request->keywords) {
            return Event::where('status', '!=', 'p')
            ->where('status', '!=', 'e')
            ->whereNotNull('name')
            ->orderByDESC('created_at')
            ->paginate(10);
        }

        return Event::where('name', 'like', '%' . $request->keywords . '%')
        ->where('status', '!=', 'p')
        ->where('status', '!=', 'e')
        ->whereNotNull('name')
        ->orderByDESC('created_at')
        ->paginate(10);
    }

    public function deletedEvents(Request $request)
    {
        return Event::onlyTrashed()->where('name', 'like', '%' . $request->keywords . '%')->paginate(10);
    }

}
