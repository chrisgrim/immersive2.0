<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CityList;
use Illuminate\Support\LazyCollection;
use App\Imports\CitiesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


class CityListsController extends Controller
{
    public function generate()
    {
        Excel::import(new CitiesImport, storage_path('city.txt'));
        
        return redirect('/')->with('success', 'All good!');

    	// $cityList = LazyCollection::make(function () {
     //    $fp = fopen(storage_path('uscit.csv'), 'r');

     //    while ($line = fgets($fp)) {
     //            yield $line;
     //        }
     //    })->each(function($line) {

     //        $list =explode("\t", $line);
                
     //            CityList::Create([
     //            'geoNameId' => $list[0],
     //            'name' => $list[1],
     //            'latitude' => $list[4],
     //            'longitude' => $list[5],
     //            ]);
            
     //    });




    }

}
