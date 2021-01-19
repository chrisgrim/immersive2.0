<?php

namespace App\Models;

use App\Scopes\RankScope;
use App\Models\ContactLevel;
use Illuminate\Database\Eloquent\Model;

class ContactLevel extends Model
{
	/**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [ 'level', 'rank', 'admin', 'user_id'];

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
	* Each ContactLevel can belong to many events
	*
	* @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	*/
    public function events() 
    {
    	return $this->belongsToMany(Event::class);
    }

    /**
     * This saves a new Contact Level type
     *
     * @return  nothing
     */
    public static function saveContactLevel($request) 
    {
        ContactLevel::create([
            'level' => $request->level,
            'admin' => true,
            'user_id' => auth()->user()->id
        ]);
    }

     /**
     * This updates a ContactLevel type
     *
     * @return nothing
     */
    public function updateContactLevel($request) 
    {
        $this->update([
            'rank' => $request->rank,
            'level' => $request->level,
            'user_id' => auth()->user()->id
        ]);
    }
}
