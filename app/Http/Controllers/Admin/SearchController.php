<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\Genre;
use App\Models\User;
use App\Models\Curated\Community;
use Illuminate\Support\Arr;
use Elastic\ScoutDriverPlus\Support\Query;
use App\Actions\Search\SearchActions;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Return list of Organizers.
     *
     * @return \Illuminate\Http\Response
     */
    public function organizers(Request $request, SearchActions $searchActions)
    {
        $builder = Organizer::searchQuery($searchActions->nameSearch($request))
            ->load(['user', 'users'])
            ->paginate(30);

        $filter = tap($builder->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($filter);
    }

    /**
     * Returned filtered Genres.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function genres(Request $request, SearchActions $searchActions)
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
    public function events(Request $request, SearchActions $searchActions)
    {
        $builder = Event::searchQuery($searchActions->nameSearch($request))
            ->load(['user','clicks','category', 'location', 'remotelocations', 'organizer', 'curatedCheck', 'shows'])
            ->paginate(10);

        $filter = tap($builder->toArray(), function (array &$content) {
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

        $query = Query::multiMatch()
            ->fields(['name', 'name._2gram','name._3gram', 'email', 'email._2gram', 'email._3gram'])
            ->type('bool_prefix')
            ->query($request->keywords);

        $builder = User::searchQuery($query)
            ->paginate(10);

        $filter = tap($builder->toArray(), function (array &$content) {
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
    public function communities(Request $request, SearchActions $searchActions)
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
