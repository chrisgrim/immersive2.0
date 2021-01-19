<?php

namespace App\Traits;

use App\Models\Flag;
use Illuminate\Support\Facades\Auth;

trait Flaggable
{
    public function flags()
    {
        return $this->morphMany(Flag::class, 'flaggable');
    }

    /**
     * This model has many ratings.
     *
     * @param mixed $rating
     * @param mixed $value
     *
     * @return Rating
     */
    public function flag($value)
    {
        $flag = Flag::query()
            ->where('flaggable_type', '=', get_class($this))
            ->where('flaggable_id', '=', $this->id)
            ->where('user_id', '=', Auth::id())
            ->first()
        ;
        if ($flag) {
            //
        } else {
            $flag = new flag();
            $flag->helpful = $value;
            $flag->user_id = Auth::id();
            $this->flags()->save($flag);
        }

    }

    public function timesRated()
    {
        return $this->flags()->count();
    }

    // Getters

    public function getFlagAmountAttribute()
    {
        return $this->timesRated();
    }
}
