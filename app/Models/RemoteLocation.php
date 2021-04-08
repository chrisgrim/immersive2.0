<?php

namespace App\Models;

use App\Scopes\RankScope;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\QueryDsl;
use Illuminate\Database\Eloquent\Model;

class RemoteLocation extends Model
{
    use Searchable;
    use QueryDsl;

    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
        'name','admin', 'user_id', 'rank', 'description', 'slug'
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
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            "name" => $this->name ,
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

}
