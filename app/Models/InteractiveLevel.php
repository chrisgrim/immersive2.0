<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InteractiveLevel;
use App\Scopes\RankScope;

class InteractiveLevel extends Model
{
     /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
        'name','description', 'rank'
    ];

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
     * Each genre can belong to many Events
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function events() 
    {
        return $this->hasMany(Event::class);
    }

    /**
     * This saves a new Interactive Level type
     *
     * @return  nothing
     */
    public static function saveInteractiveLevel($request) 
    {
        InteractiveLevel::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }

     /**
     * This updates a Interactive Level type
     *
     * @return nothing
     */
    public function updateInteractiveLevel($request) 
    {
        $this->update([
            'name' => $request->name,
            'rank' => $request->rank,
            'description' => $request->description,
        ]);
    }
}
