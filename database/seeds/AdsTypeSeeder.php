<?php

use App\Models\AdsType;
use Illuminate\Database\Seeder;

class AdsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $data) {
            AdsType::updateOrCreate(
                ['name' => $data['name']],
                [
                    'name'          => $data['name'],
                    'description'   => $data['description'],
                    'color'         => $data['color'],
                ],
            );
        }
    }

    public function getData()
    {
        return [
            ['name' => 'Highlighted Banner', 'description' => 'Banner', 'color' => '#f6993f'],
            ['name' => 'Top Row Ads', 'description' => 'Top row positioned ads', 'color' => '#3490dc'],
            ['name' => 'Highlighted Ads', 'description' => 'Ads', 'color' => '#6574cd'],
        ];
    }
}
