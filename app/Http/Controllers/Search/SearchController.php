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
            // ->boostIndex(CityList::class, 2)
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

    public function location(Request $request) 
    {
        if ($request->keywords) {
            $cityResult = CityList::multiMatchSearch()
                ->fields(['name'])
                ->query($request->keywords)
                ->type('bool_prefix')
                ->sort('rank', 'desc')
                ->sort('population', 'desc')
                ->size(6)
                ->execute();
            return $cityResult->matches();
        }
        $searchResult = CityList::matchAllSearch()
        ->sort('rank', 'desc')
        ->size(6)
        ->execute();
        return $searchResult->matches();
    }

    public function events(Request $request)
    {
        if ($request->keywords) {
            $events = Event::multiMatchSearch()
                ->join(Organizer::class)
                ->fields(['name'])
                ->query($request->keywords)
                ->type('bool_prefix')
                ->size(6)
                ->execute();
            return $events->matches();
        }
        $events = Event::matchAllSearch()
        // ->sort('rank', 'desc')
        ->size(6)
        ->execute();
        return $events->matches();
    }

    public function tags(Request $request)
    {
        if ($request->keywords) {
            $tags = Category::multiMatchSearch()
                ->join(Genre::class)
                ->fields(['name'])
                ->query($request->keywords)
                ->type('bool_prefix')
                ->size(6)
                ->execute();
            return $tags->matches();
        }
        $tags = Category::matchAllSearch()
        ->join(Genre::class)
        ->sort('rank', 'desc')
        ->size(6)
        ->execute();
        return $tags->matches();
    }

    // public function searchLocation(Request $request)
    // {
    //     if ($request->keywords) {
    //         $city = CityList::search($request->keywords)
    //         ->rule(CityListSearchRule::class)
    //         ->orderBy('rank', 'desc')
    //         ->orderBy('population', 'desc')
    //         ->get();
    //     } else {
    //         $city = CityList::search('*')
    //         ->orderBy('rank', 'desc')
    //         ->orderBy('population', 'desc')
    //         ->take(10)
    //         ->get();
    //     }

    //     if ($city->count()) {
    //         return [
    //             'data' => $city,
    //         ];
    //     }
    // }

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
    /*
     * Update the order for sections
    */
    public function community(Request $request)
    {
        if ($request->keywords) {
            $val = Community::multiMatchSearch()
                ->fields(['name'])
                ->query($request->keywords)
                ->type('bool_prefix')
                ->size(6)
                ->execute();
            return $val->matches();
        }
        $val = Community::matchAllSearch()
        ->size(6)
        ->execute();
        return $val->matches();
    }
    /*
     * Update the order for sections
    */
    public function shelf(Request $request)
    {
        if ($request->keywords) {
            $val = Shelf::multiMatchSearch()
                ->fields(['name'])
                ->query($request->keywords)
                ->type('bool_prefix')
                ->size(6)
                ->load(['community'])
                ->execute();
            return $val->matches();
        }
        $val = Shelf::matchAllSearch()
        ->size(6)
        ->load(['community'])
        ->execute();
        return $val->matches();
    }
}
