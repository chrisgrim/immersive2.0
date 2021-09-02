<?php

namespace App\Models;

use App\Scopes\RankScope;
use Illuminate\Database\Eloquent\Model;
use App\Models\MobilityAdvisory;
use Illuminate\Support\Str;

class MobilityAdvisory extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [ 'mobilities','admin', 'user_id', 'rank', 'slug' ];

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
    *Save advisories and update pivot
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  $event
    */
    public static function saveAdvisories($event, $request)
    {
        if ($request->has('mobilities')) {
            foreach ($request['mobilities'] as $content) {
                MobilityAdvisory::firstOrCreate([
                    'slug' => Str::slug($content)
                ],
                [
                    'user_id' => auth()->user()->id,
                    'mobilities' => $content
                ]);
            };
            $newSync = MobilityAdvisory::whereIn('slug', collect($request->mobilities)->map(function ($item) {
                return Str::slug($item);
            })->toArray())->get();
            $event->mobilityadvisories()->sync($newSync);
        };
    }

    /**
     * This saves a new Mobilities Level type
     *
     * @return  nothing
     */
    public static function saveMobilitiesLevel($request) 
    {
        MobilityAdvisory::create([
            'mobilities' => $request->mobilities,
            'slug' => Str::slug($request->mobilities),
            'admin' => true,
            'user_id' => auth()->user()->id
        ]);
    }

     /**
     * This updates a Mobilities Level type
     *
     * @return nothing
     */
    public function updateMobilitiesLevel($request) 
    {
        $this->update([
            'rank' => $request->rank,
            'user_id' => auth()->user()->id,
            'mobilities' => $request->mobilities,
            'slug' => Str::slug($request->mobilities),
        ]);
    }
}
