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
use App\Models\Search;
use App\Models\RemoteLocation;
use App\Models\Featured\Dock;
use DB;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Arr;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if ($request->category) { $request->request->add(['category' => explode(",",$request->category)]); }
        if ($request->tag) { $request->request->add(['tag' => explode(",",$request->tag)]); }
        if ($request->price0) { $request->request->add(['price' => [$request->price0,$request->price1 ]]); }
        if ($request->start) { $request->request->add(['dates' => [$request->start, $request->end]]); }
        if ($request->live) {$request->request->add([
            'mapboundary' => [
                '_northEast' => [ 'lat' => $request->NElat,'lng' => $request->NElng ],
                '_southWest' => [ 'lat' => $request->SWlat,'lng' => $request->SWlng ],
            ]
        ]);}

        $maxprice = ceil(Event::getMostExpensive());
        $categories = Category::all();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();

        $eventFilter = Event::boolSearch()
        ->must('range', ['closingDate' => [ 'gte' => 'now/d']])
        ->must('match', ['hasLocation' => true])
        ->when($request->price, function ($builder) use ($request) {
            return $builder->must('range', ['priceranges.price' => [ 'gte' => $request->price[0],'lte' => $request->price[1]]]);
        })
        ->when($request->category, function ($builder) use ($request) {
            return $builder->filter('terms', ['category_id' => $request->category]);
        })
        ->when($request->tag, function ($builder) use ($request) {
            return $builder->filter('terms', ['genres.name' => $request->tag]);
        })
        ->when($request->dates, function ($builder) use ($request) {
            return $builder->should('range', ['shows.date' => [ 'gte' => $request->dates[0],'lte' => $request->dates[1]]])
                ->should('term', ['showtype' => 'a'])
                ->minimumShouldMatch(1);
        })
        ->when($request->mapboundary, function ($builder) use ($request) {
            return $builder->filter('geo_bounding_box', ['location_latlon' => [
                'top_right' => 
                    [
                        'lat' => $request->mapboundary['_northEast']['lat'],
                        'lon' => $request->mapboundary['_northEast']['lng'],
                    ],
                'bottom_left' =>
                    [
                        'lat' => $request->mapboundary['_southWest']['lat'],
                        'lon' => $request->mapboundary['_southWest']['lng'],
                    ]
                ]
            ]);
        })
        ->when($request->lat, function ($builder) use ($request) {
            return $builder->filter('geo_distance', ['distance' => '40km', 'location_latlon' => [
                'lat' => $request->lat,
                'lon' => $request->lng,
            ]]);
        })
        ->load(['genres', 'category'])
        ->sortRaw(['published_at' => 'desc'])
        ->paginate(20);

        $eventContent = tap($eventFilter->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        $searchedevents = json_encode($eventContent);

        $docks = Dock::where('location', 'search')->with(['posts.limitedCards', 'shelves.publishedPosts.limitedCards', 'communities'])->orderBy('order', 'ASC')->get();

        return view('events.search',compact('searchedevents', 'docks', 'categories', 'maxprice', 'tags'));
    }

    public function mapBoundary(Request $request)
    {
        $eventFilter = Event::boolSearch()
        ->must('range', ['closingDate' => [ 'gte' => 'now/d']])
        ->must('match', ['hasLocation' => true])
        ->when($request->price, function ($builder) use ($request) {
            return $builder->must('range', ['priceranges.price' => [ 'gte' => $request->price[0],'lte' => $request->price[1]]]);
        })
        ->when($request->category, function ($builder) use ($request) {
            return $builder->filter('terms', ['category_id' => $request->category]);
        })
        ->when($request->tag, function ($builder) use ($request) {
            return $builder->filter('terms', ['genres.name' => $request->tag]);
        })
        ->when($request->dates, function ($builder) use ($request) {
            return $builder->should('range', ['shows.date' => [ 'gte' => $request->dates[0],'lte' => $request->dates[1]]])
                ->should('term', ['showtype' => 'a'])
                ->minimumShouldMatch(1);
        })
        ->when($request->mapboundary, function ($builder) use ($request) {
            return $builder->filter('geo_bounding_box', ['location_latlon' => [
                'top_right' => 
                    [
                        'lat' => $request->mapboundary['_northEast']['lat'],
                        'lon' => $request->mapboundary['_northEast']['lng'],
                    ],
                'bottom_left' =>
                    [
                        'lat' => $request->mapboundary['_southWest']['lat'],
                        'lon' => $request->mapboundary['_southWest']['lng'],
                    ]
                ]
            ]);
        })
        ->when($request->lat, function ($builder) use ($request) {
            return $builder->filter('geo_distance', ['distance' => '40km', 'location_latlon' => [
                'lat' => $request->lat,
                'lon' => $request->lng,
            ]]);
        })
        ->load(['genres', 'category'])
        ->sortRaw(['published_at' => 'desc'])
        ->paginate(20);

        $eventContent = tap($eventFilter->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($eventContent);
    }

    public function allSearch(Request $request)
    {
        $request = Search::convertUrl($request);

        $categories = Category::all();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();

        $searchRequest = Event::boolSearch()
        ->must('range', ['closingDate' => [ 'gte' => 'now/d']])
        ->when($request->price, function ($builder) use ($request) {
            return $builder->must('range', ['priceranges.price' => [ 'gte' => $request->price[0],'lte' => $request->price[1]]]);
        })
        ->when($request->category, function ($builder) use ($request) {
            return $builder->filter('terms', ['category_id' => $request->category]);
        })
        ->when($request->tag, function ($builder) use ($request) {
            return $builder->filter('terms', ['genres.name' => $request->tag]);
        })
        ->when($request->dates, function ($builder) use ($request) {
            return $builder->should('range', ['shows.date' => [ 'gte' => $request->dates[0],'lte' => $request->dates[1]]])
                ->should('term', ['showtype' => 'a'])
                ->minimumShouldMatch(1);
        })
        ->sortRaw(['published_at' => 'desc'])
        ->load(['genres', 'category'])
        ->paginate(12);

        $content = tap($searchRequest->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        $allevents = json_encode($content);
        
        return view('events.searchall',compact('allevents', 'categories', 'tags'));
    }

    public function fetch(Request $request)
    {
        $request = Search::convertUrl($request);

        $events = Event::boolSearch()
        ->must('range', ['closingDate' => [ 'gte' => 'now/d']])
        ->when($request->price, function ($builder) use ($request) {
            return $builder->must('range', ['priceranges.price' => [ 'gte' => $request->price[0],'lte' => $request->price[1]]]);
        })
        ->when($request->category, function ($builder) use ($request) {
            return $builder->filter('terms', ['category_id' => $request->category]);
        })
        ->when($request->tag, function ($builder) use ($request) {
            return $builder->filter('terms', ['genres.name' => $request->tag]);
        })
        ->when($request->dates, function ($builder) use ($request) {
            return $builder->should('range', ['shows.date' => [ 'gte' => $request->dates[0],'lte' => $request->dates[1]]])
                ->should('term', ['showtype' => 'a'])
                ->minimumShouldMatch(1);
        })
        ->sortRaw(['published_at' => 'desc'])
        ->load(['genres', 'category'])
        ->paginate(12);

        $content = tap($events->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($content);
    }

    public function online(Request $request)
    {
        $request = Search::convertUrl($request);

        $events = Event::boolSearch()
        ->must('range', ['closingDate' => [ 'gte' => 'now/d']])
        ->must('match', ['hasLocation' => false])
        ->when($request->price, function ($builder) use ($request) {
            return $builder->must('range', ['priceranges.price' => [ 'gte' => $request->price[0],'lte' => $request->price[1]]]);
        })
        ->when($request->category, function ($builder) use ($request) {
            return $builder->filter('terms', ['category_id' => $request->category]);
        })
        ->when($request->tag, function ($builder) use ($request) {
            return $builder->filter('terms', ['genres.name' => $request->tag]);
        })
        ->when($request->dates, function ($builder) use ($request) {
            return $builder->should('range', ['shows.date' => [ 'gte' => $request->dates[0],'lte' => $request->dates[1]]])
                ->should('term', ['showtype' => 'a'])
                ->minimumShouldMatch(1);
        })
        ->sortRaw(['published_at' => 'desc'])
        ->load(['genres', 'category'])
        ->paginate(12);

        $content = tap($events->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($content);
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

    public function list(Request $request)
    {
        if (! $request->keywords) return Event::where('status','p')->paginate(10);
        
        $events = Event::multiMatchSearch()
            ->fields(['name', 'name._2gram','name._3gram'])
            ->query($request->keywords)
            ->type('bool_prefix')
            ->sort('rank', 'desc')
            ->paginate(10);

        if ( $events->count() !== 0 ) {
            $filter = tap($events->toArray(), function (array &$content) {
                $content['data'] = Arr::pluck($content['data'], 'model');
            });
            return json_encode($filter);
        } else {
            return Event::Where('name', 'like', '%' . $request->keywords . '%')->paginate(10);
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
