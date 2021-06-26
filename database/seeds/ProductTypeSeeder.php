<?php

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $data) {
            ProductType::updateOrCreate(
                ['name' => $data['name']],
                [
                    'name'          => $data['name'],
                    'description'   => $data['description'],
                ],
            );
        }
    }

    public function getData()
    {
        return [
            ['name' => ProductType::TYPE_SUBSCRIPTION, 'description' => 'Subcription plan'],
        ];
    }
}
