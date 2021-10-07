<?php

namespace App\Models\Featured;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Association extends MorphPivot
{
    use HasFactory;
    protected $table = 'associations';
}
