<?php

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE TABLE ' . (new Language())->getTable());

        foreach ($this->getData() as $data) {
            Language::create([
                'name' => $data['name'],
                'code' => $data['code']
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function getData()
    {
        return [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Chinese', 'code' => 'cn']
        ];
    }
}
