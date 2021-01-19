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
use DB;
use Carbon\Carbon;
use Session;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function nav(Request $request)
    {
        if ($request->keywords) {
            $searchResult = Event::multiMatchSearch()
            ->join(Organizer::class)
            ->join(Category::class)
            ->join(Genre::class)
            ->join(CityList::class)
            ->fields(['name', 'name._2gram','name._3gram'])
            ->query($request->keywords)
            // ->analyzer('rebuilt_english')
            ->type('bool_prefix')
            ->sortRaw([
                [
                    'priority' => ['order' => 'desc', 'unmapped_type' =>'integer']
                ],
                [
                    'population' => ['order' => 'desc', 'unmapped_type' =>'integer']
                ]
                ])
            ->execute();
            if (count($searchResult->matches())) {
                return $searchResult->matches();
            }
            $searchResult = Event::matchSearch()
            ->field('name')
            ->query($request->keywords)
            ->fuzziness('AUTO')
            ->size(10)
            ->execute();
            return $searchResult->matches();
        }
        $searchResult = Event::matchAllSearch()
        ->size(10)
        ->execute();
        return $searchResult->matches();

    }

    public function location(Request $request) 
    {
        if ($request->keywords) {
            $cityResult = CityList::multiMatchSearch()
                ->fields(['name'])
                ->query($request->keywords)
                ->type('bool_prefix')
                ->sort('rank', 'desc')
                ->sort('population', 'desc')
                ->size(4)
                ->execute();
            return $cityResult->matches();
        }
        $searchResult = CityList::matchAllSearch()
        ->sort('rank', 'desc')
        ->size(10)
        ->execute();
        return $searchResult->matches();
    }

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

    public function searchLocation(Request $request)
    {
        if ($request->keywords) {
            $city = CityList::search($request->keywords)
            ->rule(CityListSearchRule::class)
            ->orderBy('rank', 'desc')
            ->orderBy('population', 'desc')
            ->get();
        } else {
            $city = CityList::search('*')
            ->orderBy('rank', 'desc')
            ->orderBy('population', 'desc')
            ->take(10)
            ->get();
        }

        if ($city->count()) {
            return [
                'data' => $city,
            ];
        }
    }

    public function searchEvents(Request $request)
    {
        if ($request->keywords) {
            $events = Event::search($request->keywords)
                ->rule(EventSearchRule::class)
                ->get();
             if ($events->count()) {
                return $events;  
            }
        }
        return Event::where('status','p')->take(10)->get();
    }

    public function searchBoneyard(Request $request)
    {
        return Event::onlyTrashed()->where('name', 'like', '%' . $request->keywords . '%')->get();
        return Event::onlyTrashed()->take(10)->get();
    }
    public function searchOrganizer(Request $request)
    {
        if($request->keywords) {
            $organizers = Organizer::search($request->keywords)
            ->rule(OrganizerSearchRule::class)
            ->with(['user'])
            ->get();
            return $organizers;
        };
    }    
}
