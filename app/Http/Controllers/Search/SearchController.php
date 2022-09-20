<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Genre;
use App\Models\Category;
use App\Models\Date;
use App\Models\User;
use App\Models\Organizer;
use App\Models\CityList;
use App\Models\SearchData;
use App\Models\RemoteLocation;
use App\Models\Curated\Community;
use App\Models\Curated\Shelf;
use App\Actions\Search\SearchActions;
use DB;
use Carbon\Carbon;
use Session;
use Elastic\ScoutDriverPlus\Support\Query;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function allsearch(Request $request)
    {
        $maxprice = ceil(Event::getMostExpensive());
        $categories = Category::all();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();

        if ($request->price || $request->category || $request->tag || $request->dates ) {
            $onlineevents = Event::search('a')
            ->rule(EventRemoteSearchRule::class)
            ->where('hasLocation', false)
            ->with(['organizer','category', 'genres'])
            ->orderBy('published_at', 'desc')
            ->paginate(8);
        } else {
            $onlineevents =  Event::where('hasLocation', false)
            ->with(['organizer','category', 'genres'])
            ->whereDate('closingDate', '>=', date("Y-m-d"))
            ->orderByDesc('rank')
            ->paginate(8);
        }

        return view('events.searchonline',compact('onlineevents', 'categories', 'maxprice', 'tags'));
    }

    public function location(Request $request, SearchActions $searchActions)
    {
        $results = CityList::searchQuery($searchActions->nameSearch($request))
            ->sort('rank', 'desc')
            ->sort('population', 'desc')
            ->execute();
        return $results->hits();
    }

    public function events(Request $request, SearchActions $searchActions)
    {
        $results = Event::searchQuery($searchActions->nameSearch($request))
            ->size(10)
            ->execute();
        return $results->hits();
    }

    public function eventorganizer(Request $request, SearchActions $searchActions)
    {
        $results = Event::searchQuery($searchActions->nameSearch($request))
            ->join(Organizer::class)
            ->size(6)
            ->execute();
        return $results->hits();
    }

    public function tags(Request $request, SearchActions $searchActions)
    {
        $results = Category::searchQuery($searchActions->nameSearch($request))
                ->join(Genre::class)
                ->size(6)
                ->execute();
        return $results->hits();
    }

    public function searchBoneyard(Request $request)
    {
        return Event::onlyTrashed()->where('name', 'like', '%' . $request->keywords . '%')->get();
        return Event::onlyTrashed()->take(10)->get();
    }
    
    public function community(Request $request, SearchActions $searchActions)
    {
        $results = Community::searchQuery($searchActions->nameSearch($request))
                ->size(6)
                ->execute();
        return $results->hits();
    }
    /*
     * search for shelves
    */
    public function shelf(Request $request, SearchActions $searchActions)
    {
        $results = Shelf::searchQuery($searchActions->nameSearch($request))
            ->size(6)
            ->load(['community'])
            ->execute();
        return $results->hits();
    }
}
