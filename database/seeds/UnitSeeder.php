<?php

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $data) {
            Unit::firstOrCreate([
                'display' => $data['display']
            ], [
                'name' => $data['name'],
                'description' => $data['description']
            ]);
        }
    }

    private function getData()
    {
        return [
            ['name' => 'Square Feets', 'display' => 'sf', 'description' => null],
        ];
    }
}
