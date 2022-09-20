<?php

namespace App\Models;

use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;


class Organizer extends Model
{
    use Searchable;

    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
    	'user_id','name','website','slug','description','rating','largeImagePath','thumbImagePath','instagramHandle','twitterHandle','facebookHandle', 'email', 'status', 'patreon'
    ];

     /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['hexColor', 'showRating'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            "name" => $this->name ,
            "email" => $this->email,
            'priority' => 3,
        ];
    }

    /**
    * What events should be searchable for scout elastic search
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function shouldBeSearchable()
    {
        return $this->status == 'p';
    }

    /**
    * Determines which events are published for Laravel Scout
    *
    * @return bool
    */
    public function isPublished() {
        return $this->status == 'p';
    }

    public function getHandles(){
        $result = [];
        if ($this->instagramHandle) {
            array_push($result, "https://www.instagram.com/{$this->instagramHandle}");
        }
        if ($this->facebookHandle) {
            array_push($result, "https://www.facebook.com/{$this->facebookHandle}");
        }
        if ($this->twitterHandle) {
            array_push($result, "https://www.twitter.com/{$this->twitterHandle}");
        }
        return $result;
    }

    /**
    * Each Organizer can have many events
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function events() 
    {
        return $this->hasMany(Event::class)->orderByDesc('updated_at');
    }

    /**
    * Each Organizer can have many un archived events
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function listedEvents() 
    {
        return $this->hasMany(Event::class)->orderByDesc('updated_at')->where('archived', false);
    }

    /**
    * Each Organizer can have many un archived events
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function archivedEvents() 
    {
        return $this->hasMany(Event::class)->orderByDesc('updated_at')->where('archived', true);
    }
    
    /**
     * The Organizer belongs to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The Organizer belongs to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function owner() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
                        ->withPivot('role')
                        ->withTimestamps()
                        ->as('membership');
    }

    /**
     * Get all of the team's users including its owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allUsers()
    {
        return $this->users->merge([$this->owner]);
    }

    /**
    * Assign the current user a color
    *
    * @return string
    */
    public function gethexColorAttribute()
    {
        $myarray = ['#2F405F','#DA5E8E','#20B7A6','#749EEB','#1EAA9A']; 
        return $myarray[$this->id % count($myarray)];
    }

    /**
    * Get Organizer Rating
    *
    * @return boolean
    */
    public function getShowRatingAttribute()
    {
        $ids = $this->events()->get()->pluck('id');
        $data['count'] = Rating::where('rateable_type', Event::class)->whereIn('rateable_id', $ids)->count();
        $data['rating'] = number_format(Rating::where('rateable_type', Event::class)->whereIn('rateable_id', $ids)->average('rating'), 1);
        return $data;
    }

    /**
     * Get Past Events for organizer
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function pastEvents()
    {
        return $this->hasMany(Event::class)
                    ->whereDate('closingDate', '<', Carbon::today())
                    ->orderBy('created_at', 'ASC');
    }

    /**
     * Get In Progress Events for organizer
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function inProgressEvents()
    {
        return $this->hasMany(Event::class)
                    ->where(function ($query) {
                        $query->whereDate('closingDate', '>=', Carbon::today())
                            ->orWhereNull('closingDate');
                    })
                    ->orderBy('created_at', 'ASC');
    }

    /**
    * Save File and update organizer model with path name
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  $organizer
    */
    public static function getOrganizerEvents()
    {
        $organizers = auth()->user()->allOrganizers();

        foreach ($organizers as $organizer) {
            $organizer->load('listedEvents.shows', 'archivedEvents.shows');
        }

        return $organizers;
    }

    /**
    * Deletes the event images and then deletes event
    *
    * @return Nothing
    */
    public function deleteOrganizer($organizer) 
    {
        if ($organizer->users()->exists()) { $organizer->users()->detach(); }
        foreach ($organizer->events as $event) { $event->delete(); }
        $organizer->delete();
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
}
