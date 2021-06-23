<?php

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE TABLE ' . (new Module())->getTable());

        $sorted_data = collect($this->getData())->sortBy('name');

        foreach ($sorted_data as $data) {
            Module::create([
                'name'          =>  $data['name'],
                'display'       =>  $data['display'],
                'description'   =>  $data['description']
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
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
            ['name' => 'category', 'display' => 'category', 'description' => 'Category Module'],
        ];
    }
}
