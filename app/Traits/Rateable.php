<?php

namespace App\Traits;

use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

trait Rateable
{
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable')->orderBy('created_at', 'desc')->limit(5);
    }

    /**
     * This model has many ratings.
     *
     * @param mixed $rating
     * @param mixed $value
     *
     * @return Rating
     */
    public function rate($value, $comment, $date)
    {
        $rating = new Rating();
        $rating->rating = $value;
        $rating->comment = $comment;
        $rating->date = $date;
        $rating->user_id = Auth::id();

        $this->ratings()->save($rating);
    }

    public function rateOnce($value, $comment, $date)
    {
        $rating = Rating::query()
            ->where('rateable_type', '=', get_class($this))
            ->where('rateable_id', '=', $this->id)
            ->where('user_id', '=', Auth::id())
            ->first()
        ;

        if ($rating) {
            $rating->rating = $value;
            $rating->comment = $comment;
            $rating->date = $date;
            $rating->save();
        } else {
            $this->rate($value, $comment, $date);
        }
    }

    public function averageRating()
    {
        return number_format($this->ratings()->avg('rating'), 1);
    }

    public function sumRating()
    {
        return $this->ratings()->sum('rating');
    }

    public function timesRated()
    {
        return $this->ratings()->count();
    }

    public function usersRated()
    {
        return $this->ratings()->groupBy('user_id')->pluck('user_id')->count();
    }

    public function userHasRated()
    {
        return $this->ratings()->where('user_id', '=', Auth::id())->exists();
    }

    public function userAverageRating()
    {
        return $this->ratings()->where('user_id', Auth::id())->avg('rating');
    }

    public function userSumRating()
    {
        return $this->ratings()->where('user_id', Auth::id())->sum('rating');
    }

    public function ratingPercent($max = 5)
    {
        $quantity = $this->ratings()->count();
        $total = $this->sumRating();

        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    // Getters

    public function getAverageRatingAttribute()
    {
        return $this->averageRating();
    }

    public function getHasRatedAttribute()
    {
        return $this->userHasRated();
    }

    public function getSumRatingAttribute()
    {
        return $this->sumRating();
    }

    public function getUserAverageRatingAttribute()
    {
        return $this->userAverageRating();
    }

    public function getUserSumRatingAttribute()
    {
        return $this->userSumRating();
    }

    public function getRatingAmountAttribute()
    {
        return $this->timesRated();
    }
}
