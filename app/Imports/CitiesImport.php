<?php

namespace App\Imports;

use App\Models\CityList;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CitiesImport implements ToModel, WithHeadingRow, WithProgressBar, WithChunkReading
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row['country'] == 'US') {
            $name = $row['city'] . ', ' . $row['state_id'];
        } else {
            $name = $row['city'] . ', ' . $row['country'];;
        }
        return new CityList([
            'name' => $name,
            'latitude' => $row['lat'],
            'longitude' => $row['lng'],
            'population' => $row['population'],
            'country' => $row['country'],
        ]);
    }
    public function batchSize(): int
    {
        return 100;
    }
    public function chunkSize(): int
    {
        return 400;
    }
}
