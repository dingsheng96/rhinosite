<?php

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE TABLE ' . app(Service::class)->getTable());

        collect($this->getData())
            ->sortBy('name')
            ->each(function ($data, $key) {
                Service::create([
                    'name'          =>  $data['name'],
                    'description'   =>  $data['description'],
                ]);
            });

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    private function getData()
    {
        return [
            ['name' => 'Wall & Partition', 'description' => null],
            ['name' => 'Wiring ', 'description' => null],
            ['name' => 'Piping', 'description' => null],
            ['name' => 'Air-con', 'description' => null],
            ['name' => 'Window', 'description' => null],
            ['name' => 'Door', 'description' => null],
            ['name' => 'Ceiling', 'description' => null],
            ['name' => 'Gate', 'description' => null],
            ['name' => 'Alarm & Security', 'description' => null],
            ['name' => 'Roof', 'description' => null],
            ['name' => 'Solar', 'description' => null],
            ['name' => 'Flooring', 'description' => null],
            ['name' => 'Furniture', 'description' => null],
            ['name' => 'Grille', 'description' => null],
            ['name' => 'Painting & Wallpaper', 'description' => null],
            ['name' => 'Swimming Pool', 'description' => null],
            ['name' => 'Fittings', 'description' => null],
            ['name' => 'Fencing', 'description' => null],
            ['name' => 'Landscape', 'description' => null],
            ['name' => 'Sewerage', 'description' => null],
            ['name' => 'Lift & Escalator', 'description' => null],
            ['name' => 'Water Proofing & Leakage', 'description' => null],
        ];
    }
}
