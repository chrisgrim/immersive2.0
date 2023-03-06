<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuratedEventCheck extends Model
{
    use HasFactory;

    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = ['curated', 'social'];

    protected $casts = [
        'curated' => 'boolean', 'social' => 'boolean'
    ];

    /**
    * Each Curated Check belongs to One Event
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function event() 
    {
        return $this->belongsTo(Event::class);
    }
}
