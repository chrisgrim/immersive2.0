<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\QueryDsl;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\View\View;
use Laravel\Cashier\Billable;
use Illuminate\Support\Str;
use App\Models\Events\EventRequest;
use App\Models\Curated\Community;
use App\Models\Curated\Post;
use DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use Billable;
    use Searchable;
    use QueryDsl;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','largeImagePath','thumbImagePath','provider','provider_id', 'gravatar', 'type', 'email_verified_at', 'newsletter_type', 'silence', 'unread', 'reminder', 'current_team_id', 'blurb'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'hasCreatedOrganizers', 'hasMessages', 'hexColor'
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            "name" => $this->name,
            "email" => $this->email,
        ];
    }

    /**
     * User can have many events
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function events() 
    {
        return $this->hasMany(Event::class);
    }

    /**
     * The User has many organizations
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function organizers() 
    {
        return $this->hasMany(Organizer::class)
                    ->orderBy('created_at', 'DESC');
    }

    /**
     * Get all of the teams the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Organizer::class)
                        ->orderBy('created_at', 'DESC')
                        ->withPivot('role')
                        ->withTimestamps()
                        ->as('membership');
    }

    /**
     * Get all of the users's organizations including its owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allOrganizers()
    {
        return $this->organizers->merge($this->teams);
    }

    /**
     * Determine if the user owns the given organization.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function ownsOrganization($organizer)
    {
        return $this->id == $organizer->user_id;
    }

    /**
     * Determine if the user belongs to the given organization.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function belongsToOrganization($organizer)
    {
        return $this->teams->contains(function ($t) use ($organizer) {
            return $t->id === $organizer->id;
        }) || $this->ownsOrganization($organizer);
    }

    /**
     * The User can send many messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

     /**
     * The User can send many messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function modmessages()
    {
        return $this->hasMany(ModeratorComment::class);
    }

    /**
     * The User belongs to many conversations
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)->orderBy('updated_at', 'DESC')->whereNull('event_id');
    }

    /**
     * The User belongs to many conversations
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function eventconversations()
    {
        return $this->belongsToMany(Conversation::class)->orderBy('updated_at', 'DESC')->whereNotNull('event_id');
    }

    /**
     * The communities that belong to the user.
     */
    public function communities()
    {
        return $this->belongsToMany(Community::class);
    }

    /**
     * The User has many Staff Picks
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function staffpicks() 
    {
        return $this->hasMany(StaffPick::class);
    }

    /**
     * The User has many Sections
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function sections() 
    {
        return $this->hasMany(Section::class);
    }

    /**
     * The User has many Staff Picks
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function posts() 
    {
        return $this->hasMany(Post::class);
    }

    /**
     * User can have many favorites
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function favorites() 
    {
        return $this->hasMany(Favorite::class);
    }

    public function favouritedEvents()
    {
        return $this->morphedByMany(Event::class, 'favorited', 'favorites');
    }

    /**
     * Each User has One Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location() 
    {
        return $this->hasOne(UserLocation::class);
    }

    /**
    * Determine if the current user is Admin
    *
    * @return bool
    */
    public function isAdmin() {
        return $this->type === 'a';
    }

    /**
    * Determine if the current user is Moderator
    *
    * @return bool
    */
    public function isModerator() {
        return $this->type === 'm' || $this->type === 'a';
    }

    /**
    * Determine if the current user is curator
    *
    * @return bool
    */
    public function isCurator() {
        return $this->type === 'c' || $this->type === 'm' || $this->type === 'a';
    }

    /**
    * Determine if the current user has created events
    *
    * @return bool
    */
    public function getHasCreatedOrganizersAttribute()
    {
        return $this->organizers()->count() ? true : false;    
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
    * Determine if the current user has messages 
    *
    * @return bool
    */
    public function getHasMessagesAttribute()
    {
        return DB::table('conversations')
            ->where('user_one', $this->id)
            ->orWhere('user_two', $this->id)
            ->count() ? true : false;
    }


    public function getGravatar()
    {
        $hash = md5(strtolower(trim($this->email)));
        $url = "https://www.gravatar.com/avatar/$hash?d=404";
        if (!strstr(get_headers($url, 1)[0], '404 Not Found')) {
            $this->update([ 'gravatar' => "https://www.gravatar.com/avatar/$hash?s=180"]);
        }
    }

    
}
