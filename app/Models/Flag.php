<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    use HasFactory;

    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [ 'helpful', 'flagged' ];

    /**
     * Fetch the model that was flagged.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function flaggable()
    {
        return $this->morphTo();
    }

     /**
     * Each flag belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
