<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\CustomSearch;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;


class Organizer extends Model
{
    use Searchable;
    use CustomSearch;

    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
    	'user_id','name','website','slug','description','rating','largeImagePath','thumbImagePath','instagramHandle','twitterHandle','facebookHandle', 'email', 'status'
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
        return $this->hasMany(Event::class);
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
        $organizers = auth()->user()->organizers()->get();

        foreach ($organizers as $organizer) {
            $organizer->setRelation('pastEvents', $organizer->pastEvents()->paginate(7));
            $organizer->setRelation('inProgressEvents', $organizer->inProgressEvents()->paginate(7));
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
