<?php

namespace App\Imports\Settings\Country;

use App\Models\Settings\Country\City;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Settings\Country\CountryState;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;

class CityImport implements ToModel, WithValidation, SkipsEmptyRows
{
    use Importable;

    public $country_state;

    public function __construct(CountryState $country_state)
    {
        $this->country_state = $country_state;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return $this->country_state->cities()
            ->firstOrCreate(
                ['name' => $row[0]],
                ['name' => $row[0]]
            );
    }

    public function rules(): array
    {
        return [
            '*.0' => [
                'required',
                'distinct'
            ]
        ];
    }
}
