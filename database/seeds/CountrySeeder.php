<?php

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('TRUNCATE TABLE ' . app(Country::class)->getTable());

        foreach ($this->getData() as $data) {
            Country::create([
                'name'          =>  $data['name'],
                'code'          =>  $data['code'],
                'set_default'   =>  $data['set_default'],
                'currency_id'   =>  $data['currency_id'],
                'dial_code'     =>  $data['dial_code'],
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function getData()
    {
        return [
            ['name' => 'Malaysia', 'code' => 'my', 'set_default' => true, 'currency_id' => 1, 'dial_code' => '60'],
        ];
    }
}
