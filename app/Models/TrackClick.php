<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackClick extends Model
{
    use HasFactory;

    protected $fillable = [ 'event_id', 'organzier_id', 'user_id' ];
    
}
