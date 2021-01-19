<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
	protected $fillable = ['name','ticket_price','ticket_id', 'ticket_type', 'description','type', 'currency'];
    
	/**
     * Ticket Belongs to the Show Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function ticket()
    {
        return $this->morphTo();
    }

    /**
     * Gets the price Range
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public static function getPriceRange($array, $types, $currency)
    {
        if(in_array('s', $types)) {
            $type = 's';
        }
        if(in_array('f', $types)) {
            $type = 'f';
        }
        if(in_array('p', $types)) {
            $type = 'p';
        }
        rsort($array);
        if ($type == 'f') {
            $first = 'Free';
        }
        if ($type == 'p') {
            $first = 'PWYC';
        }
        if ($type == 's') {
            $first = $currency. last($array);
        }
        if (sizeof($array) > 1) {
            if (in_array('s', $types)) {
                return $pricerange = $first . ' - ' . $currency . $array[0];
            } else {
                return $pricerange = $first;
            }
        } else {
            return $pricerange = $first;
        }
    }


}
