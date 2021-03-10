<?php

namespace App\Models;

use App\Models\Conversation;
use App\Models\Genre;
use App\Traits\Rateable;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\CustomSearch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Events\EventRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\PublishedScope;

class Event extends Model
{
    use CustomSearch;
    use Favoritable;
    use Rateable;
    use SoftDeletes;
    use Searchable;

    protected $casts = [
        'location_latlon' => 'array',
        'hasLocation' => 'boolean'
    ];

    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
	protected $fillable = [
    	'slug', 'user_id', 'timezone_id', 'category_id','interactive_level_id','organizer_id','description','name','largeImagePath','thumbImagePath','advisories_id', 'organizer_id', 'location_latlon', 'closingDate','websiteUrl','ticketUrl','show_times','price_range', 'status','tag_line', 'hasLocation', 'showtype', 'embargo_date', 'remote_description', 'published_at', 'call_to_action', 'age_limits_id', 'rank'
    ];

    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    protected $with = ['favorites', 'priceranges', 'shows','age_limits', 'organizer'];

    /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = ['isFavorited', 'isShowing', 'averageRating', 'ratingAmount', 'hasRated'];

        /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new PublishedScope);
    }

    /**
    * What events should be searchable for scout elastic search
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function shouldBeSearchable()
    {
        return $this->status === 'p';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'status' => $this->status,
            'showtype' => $this->showtype,
            'rank' => $this->rank,
            'category_id' => $this->category_id,
            'location_latlon' => $this->location_latlon,
            'hasLocation' => $this->hasLocation,
            'shows' => $this->showsSelect,
            'published_at' => $this->published_at,
            'closingDate' => $this->closingDate,
            'priceranges' => $this->pricerangesSelect,
            'genres' => $this->genreSelect,
            'priority' => 5,
        ];
    }

    /**
     * Get the user events
     *
     * @return users collection of events
     */
    public function scopeUserEvents($query)
    {
        return $query->where('user_id', auth()->id());
    } 

    public function showsSelect()
    {
        return $this->hasMany(Show::class)->select('date');
    }

    public function genreSelect()
    {
        return $this->belongsToMany(Genre::class)->select('name');
    }

    public function pricerangesSelect()
    {
        return $this->hasMany(PriceRange::class)->select('price');
    }

    /**
    * Helpful command to see published events
    *
    * @return bool
    */
    public function isPublished() {
        return $this->status == 'p';
    }

    /**
    * Determines which events are published for Laravel Scout
    *
    * @return bool
    */
    public function inProgress() {
        return $this->status != 'r' && $this->status != 'p' && $this->status != 'e' && $this->status != 'n';
    }

    /**
    * Determines which events are published
    *
    * @return bool
    */
    public function getIsPickedAttribute() {
        return $this->status == 'p';
    }

    /**
    * Determines if the show is still available
    *
    * @return boolean
    */
    public function getIsShowingAttribute()
    {
        return $this->closingDate >= Carbon::now();
    }
    
    /**
    * Each event belongs to One User
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }


    // *
    // * Each event has multiple comments
    // *
    // * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    
    // public function moderatorcomments() 
    // {
    //     return $this->hasMany(ModeratorComment::class);
    // }

    /**
    * Each event has a conversation
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function conversation() 
    {
        return $this->hasOne(Conversation::class);
    }

    /**
    * Each event belongs to one Category
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function category() 
    {
        return $this->belongsTo(Category::class);
    }

    /**
    * Each event belongs to one timezone
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function timezone() 
    {
        return $this->belongsTo(Timezone::class);
    }

    /**
    * Each event hasOne StaffPick
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function staffpick() 
    {
        return $this->hasOne(StaffPick::class);
    }

    /**
    * Each event has many event reviews
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function eventreviews() 
    {
        return $this->hasMany(ReviewEvent::class)
                    ->orderBy('rank', 'ASC');
    }

    /**
    * Each event has many clicks
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function clicks() 
    {
        return $this->hasMany(TrackClick::class);
    }

    /**
    * Each event belongs to One Organizer
    *
    * @return \Illuminate\Database\Eloquent\Relations/belongsTo
    */
    public function organizer() 
    {
        return $this->belongsTo(Organizer::class);
    }

    /**
     * Each Event has One Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location() 
    {
        return $this->hasOne(Location::class);
    }

    /**
     * Each Event has one Expectation Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function advisories() 
    {
        return $this->hasOne(Advisory::class);
    }

    /**
     * Each event has many shows
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shows() 
    {
        return $this->hasMany(Show::class)->orderBy('date', 'DESC');
    }

    /**
     * Each event has many eventrequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventRequest() 
    {
        return $this->hasMany(EventRequest::class);
    }

    /**
     * Each event has many shows
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function showOnGoing() 
    {
        return $this->hasOne(ShowOnGoing::class);
    }

    /**
     * Each event has many price ranges
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function priceranges() 
    {
        return $this->hasMany(PriceRange::class);
    }

    /**
     * Each event can belong to many shows
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function genres() 
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * Each event can belong to many shows
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function age_limits() 
    {
        return $this->belongsTo(AgeLimit::class);
    }

    /**
     * Each event can belong to one interactive level
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function interactive_level() 
    {
        return $this->belongsTo(InteractiveLevel::class);
    }

    /**
     * Each event can belong to many remote types
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function remotelocations() 
    {
        return $this->belongsToMany(RemoteLocation::class);
    }

    /**
     * Each event can belong to many ContactLevels
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function contactlevels() 
    {
        return $this->belongsToMany(ContactLevel::class);
    }

    /**
     * Each event can belong to many ContactLevels
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function contentadvisories() 
    {
        return $this->belongsToMany(ContentAdvisory::class);
    }

    /**
     * Each event can belong to many ContactLevels
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function mobilityadvisories() 
    {
        return $this->belongsToMany(MobilityAdvisory::class);
    }

    /**
    * Sets the Route Key to slug instead of ID
    *
    * @return Route Key Name
    */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
    * Finds all the current live events
    *
    * @return a collection of the live events with priceranges attached
    */

    public static function getMostExpensive()
    {
        return Event::where('status', 'p')
            ->with('priceranges')
            ->whereDate('closingDate', '>=', date("Y-m-d"))
            ->get()
            ->map(function($event) { return $event->priceranges->pluck('price');})
            ->flatten()
            ->max();
    }

    /**
    * Creates a new event
    *
    * @return Nothing
    */
    public static function newEvent($request)
    {
        $event = Event::create([
            'user_id' => auth()->id(),
            'slug' => rand(),
            'organizer_id' => $request->id,
            'status' => '0',
        ]);
        $event->location()->Create();
        $event->advisories()->Create();
        return $event;
    }

    /**
    * Deletes the event images and then deletes event
    *
    * @return Nothing
    */
    public function deleteEvent($event) 
    {
        if ($event->user_id == auth()->id()) {
            if ($event->conversation()->exists()) {
                $event->conversation()->first()->delete();
            }
            $event->delete();
        }
    }

    /**
    * Check if event exists
    *
    * @return boolean
    */
    public function exists($event, $request) 
    {
        $eventExists = Event::UserEvents()->where('slug', Str::slug($request->name))->first();

        return $eventExists && $eventExists->id !== $event->id;

    }

    /**
    * Update status
    *
    * @return udpates event status
    */
    public function updateEventStatus($status, $request) 
    {
        if ($request->resubmit) {
            $this->update([ 'status' => '8' ]);
        } else {
            if ($this->status < $status + 1 && $this->inProgress()) $this->update([ 'status' => $status ]); 
        }
    }

    /**
    * Check Event Statue
    *
    * @return checks event status and returns boolean
    */
    public function checkEventStatus($status) 
    {
        return $this->status < $status && $this->inProgress();
    }

    /**
    * Deletes the event images and then deletes event
    *
    * @return Nothing
    */
    public function finalizeEvent($event) 
    {
        $website = $event->organizer->website;
        if ($event->websiteUrl == null) {
            $event->update([ 'websiteUrl' => $website ]);
        }
        if ($event->ticketUrl == null) {
            $event->update([ 'ticketUrl' => $website ]);
        }
    }

    /**
    * Deletes the event images and then deletes event
    *
    * @return Nothing
    */
    public static function finalSlug($event) 
    {
        if(Event::withTrashed()->where('slug', '=', Str::slug($event->name))->where('id', '!=', $event->id)->exists()){
            $slug = Str::slug($event->name) . '-' . substr(md5(microtime()),rand(0,26),5);
            if(Event::where('slug', '=', $slug)->exists()){
                return Str::slug($event->name) . '-' . rand(1,50000);
            } else {
                return $slug;
            };
        } else {
            return Str::slug($event->name);
        };
    }

    /**
    * Store a newly created resource in storage. Update all the standard fields. For each genre field I check if they exist then add any the user created. Finally I sync those submitted with the genres associated with the event.
    *
    * @return Nothing
    */
    public function storeDescription($request, $event) 
    {

        // $web = get_headers($request->websiteUrl, 1)[0];
        // if (strstr($web, '302') || strstr($web, '200 OK') || strstr($web, '301')) {
        // } else { return abort(404, "broken");}

        // $ticket = get_headers($request->ticketUrl, 1)[0];
        // if (strstr($ticket, '302') || strstr($ticket, '200 OK') || strstr($ticket, '301')) {
        // } else { return abort(404, "broken");}

        $event->update($request->except(['genre']));

        if ($request->has('genre')) {
            foreach ($request['genre'] as $genre) {
                Genre::firstOrCreate([
                    'slug' => Str::slug($genre)
                ],
                [
                    'name' => $genre,
                    'user_id' => auth()->user()->id,
                ]);
            };
            $newSync = Genre::whereIn('slug', collect($request->genre)->map(function ($item) {
                return Str::slug($item); 
            })->toArray())->get();
            $event->genres()->sync($newSync);
        };
    }
}
