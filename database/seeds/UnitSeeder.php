<?php

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE TABLE ' . (new Unit())->getTable());

        foreach ($this->getData() as $data) {
            Unit::create([
                'name' => $data['name'],
                'display' => $data['display'],
                'description' => $data['description']
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    private function getData()
    {
        return [
            ['name' => 'Square Feets', 'display' => 'sf', 'description' => null],
        ];
    }
}
