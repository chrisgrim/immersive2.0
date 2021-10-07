<?php

namespace App\Models\Featured;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dock extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'name', 'type', 'location', 'order' ];

    public function shelves()
    {
        return $this->morphedByMany('App\Models\Curated\Shelf', 'association')->using('App\Models\Featured\Association');
    }

    public function posts()
    {
        return $this->morphedByMany('App\Models\Curated\Post', 'association')->using('App\Models\Featured\Association');
    }

    public function communities()
    {
        return $this->morphedByMany('App\Models\Curated\Community', 'association')->using('App\Models\Featured\Association');
    }

}
