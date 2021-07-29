<?php

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement('TRUNCATE TABLE ' . app(ProductCategory::class)->getTable());

        foreach ($this->getData() as $data) {
            ProductCategory::create([
                'name'          => $data['name'],
                'description'   => $data['description'],
                'enable_slot'   => $data['enable_slot'],
            ],);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function getData()
    {
        return [
            ['name' => ProductCategory::TYPE_ADS, 'description' => 'Add On Ads', 'enable_slot' => true],
            ['name' => ProductCategory::TYPE_SUBSCRIPTION, 'description' => 'Monthly Subscription', 'enable_slot' => false],
        ];
    }
}
