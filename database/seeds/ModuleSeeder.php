<?php

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sorted_data = collect($this->getData())->sortBy('name');

        foreach ($sorted_data as $data) {
            Module::updateOrCreate(
                ['name' => $data['name']],
                [
                    'display' => $data['display'],
                    'description' => $data['description']
                ]
            );
        }
    }

    public function getData()
    {
        return [
            ['name' => 'country', 'display' => 'Countries', 'description' => 'Country Module'],
            ['name' => 'currency', 'display' => 'Currencies', 'description' => 'Currency Module'],
            ['name' => 'language', 'display' => 'Languages', 'description' => 'Language Module'],
            ['name' => 'role', 'display' => 'Roles', 'description' => 'Role Module'],
            ['name' => 'unit', 'display' => 'Units', 'description' => 'Unit Module'],
            ['name' => 'enduser', 'display' => 'End Users', 'description' => 'End User Module'],
            ['name' => 'merchant', 'display' => 'Merchants', 'description' => 'Merchant Module'],
            ['name' => 'admin', 'display' => 'Admins', 'description' => 'Admin Module'],
        ];
    }
}
