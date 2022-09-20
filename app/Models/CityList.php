<?php

namespace App\Models;

use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Model;
use Elastic\ScoutDriverPlus\Support\Query;

class CityList extends Model
{
	protected $guarded = [];

	use Searchable;

    /**
    * What events should be searchable for scout elastic search
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function shouldBeSearchable()
    {
        return $this->population > 50000 && strlen($this->name) == strlen(utf8_decode($this->name));
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            "name" => $this->name,
            "population" => $this->population,
            "rank" => $this->rank,
            'priority' => 1,
        ];
    }
}