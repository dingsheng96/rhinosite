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
            ['name' => 'country', 'display' => 'Country', 'description' => 'Country Module'],
            ['name' => 'currency', 'display' => 'Currency', 'description' => 'Currency Module'],
            ['name' => 'language', 'display' => 'Language', 'description' => 'Language Module'],
            ['name' => 'role', 'display' => 'Role', 'description' => 'Role Module'],
            ['name' => 'unit', 'display' => 'Unit', 'description' => 'Unit Module'],
            ['name' => 'member', 'display' => 'Member', 'description' => 'Member Module'],
            ['name' => 'merchant', 'display' => 'Merchant', 'description' => 'Merchant Module'],
            ['name' => 'admin', 'display' => 'Admin', 'description' => 'Admin Module'],
            ['name' => 'project', 'display' => 'Project', 'description' => 'Project Module'],
            ['name' => 'ads', 'display' => 'Ads', 'description' => 'Ads Module'],
            ['name' => 'order', 'display' => 'Order', 'description' => 'Order Module'],
        ];
    }
}
