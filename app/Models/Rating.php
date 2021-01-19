<?php

namespace App\Models;

use App\Traits\Flaggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    use Flaggable;

    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [ 'rating', 'comment', 'date' ];

    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    protected $with = ['flags'];

    /**
     * Fetch the model that was rated.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
