<?php

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = Currency::create([
            'name' => 'Malaysia Ringgit',
            'code' => 'MYR'
        ]);

        $currency->fromCurrencyRates()->create([
            'to_currency' => $currency->id,
            'rate' => 1
        ]);
    }
}
