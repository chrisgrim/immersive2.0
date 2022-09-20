<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Elastic\ScoutDriverPlus\Searchable;
use App\Scopes\AdminScope;
use Illuminate\Support\Str;

class Genre extends Model
{
    use Searchable;

    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
	protected $fillable = [
    	'name','admin', 'user_id', 'rank', 'slug'
    ];

    /**
    * What events should be searchable for scout elastic search
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function shouldBeSearchable()
    {
        return $this->admin === 1;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new AdminScope);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            "name" => $this->name,
            "admin" => $this->admin,
            "rank" => $this->rank,
            'priority' => 3,
        ];
    }
    
    /**
     * Each genre can belong to many Events
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function events() 
    {
    	return $this->belongsToMany(Event::class);
    }

    /**
     * This saves a new Genre type
     *
     * @return  nothing
     */
    public static function saveGenre($request) 
    {
        Genre::firstOrCreate([
            'slug' => Str::slug($request->name)
        ],
        [
            'name' => $request->name,
            'user_id' => auth()->user()->id,
            'admin' => true,
        ]);
    }

     /**
     * This updates a genre type
     *
     * @return nothing
     */
    public function updateGenre($request) 
    {
        $this->update([
            'rank' => $request->rank,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'user_id' => auth()->user()->id,
            'admin' => $request->admin,
        ]);
    }  
}
