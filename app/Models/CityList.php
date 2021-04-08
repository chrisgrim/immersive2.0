<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\QueryDsl;
use Illuminate\Database\Eloquent\Model;

class CityList extends Model
{
	protected $guarded = [];

	use Searchable;
    use QueryDsl;

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
            // "rank" => $this->rank,
            "rank" => 10,
            'priority' => 1,
        ];
    }
}