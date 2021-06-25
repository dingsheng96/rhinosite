<?php

namespace App\Imports\Settings\Country;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Country;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;

class CountryStateImport implements ToModel, WithValidation, SkipsEmptyRows
{
    use Importable;

    public $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        return $this->country->countryStates()
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
