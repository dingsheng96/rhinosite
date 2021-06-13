<?php

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $data) {
            Language::updateOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name']]
            );
        }
    }

    public function getData()
    {
        return [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Chinese', 'code' => 'cn']
        ];
    }
}
