<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $fillable = [];

    /**
    * Each timezone has many events
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function events() 
    {
        return $this->hasMany(Event::class);
    }
}
