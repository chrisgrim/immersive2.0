<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\RankScope;
use Illuminate\Support\Str;

class ContentAdvisory extends Model
{
	/**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [ 'advisories','admin', 'user_id', 'rank', 'slug' ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new RankScope);
    }
    
    /**
	* Each ContentAdvisory can belong to many events
	*
	* @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	*/
    public function events() 
    {
    	return $this->belongsToMany(Event::class);
    }

    /**
    *Save advisories and updates pivot
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  $event
    */
    public static function saveAdvisories($event, $request)
    {
        if ($request->has('contentAdvisory')) {
            foreach ($request['contentAdvisory'] as $content) {
                ContentAdvisory::firstOrCreate([
                    'slug' => Str::slug($content)
                ],
                [
                    'user_id' => auth()->user()->id,
                    'advisories' => $content,
                ]);
            };
            $newSync = ContentAdvisory::whereIn('slug', collect($request->contentAdvisory)->map(function ($item) {
                return Str::slug($item); 
            })->toArray())->get();
            $event->contentadvisories()->sync($newSync);
        };
    }

    /**
    *Admin saving a specific advisory
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  $event
    */
    public static function saveAdvisory($request)
    {
        ContentAdvisory::create([
            'advisories' => $request->advisories,
            'slug' => Str::slug($request->advisories),
            'admin' => true,
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * This updates a ContactLevel type
     *
     * @return nothing
     */
    public function updateAdvisory($request) 
    {
        $this->update([
            'rank' => $request->rank,
            'user_id' => auth()->user()->id,
            'advisories' => $request->advisories,
            'slug' => Str::slug($request->advisories),
        ]);
    }
}
