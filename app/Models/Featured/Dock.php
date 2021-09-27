<?php

namespace App\Models\Featured;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dock extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'name', 'type' ];

    /**
     * Get the featured for the section .
     */
    public function featured()
    {
        return $this->belongsToMany(Feature::class)->withPivot('order')->orderBy('order', 'ASC');
    }
}
