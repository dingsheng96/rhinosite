<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $data) {
            Role::firstOrCreate(
                ['name' => $data['name']],
                [
                    'name' => $data['name'],
                    'guard_name' => $data['guard_name'],
                    'description' => $data['description']
                ]
            );
        }
    }

    private function getData()
    {
        return [
            ['name' => 'Super Admin', 'guard_name' => config('auth.defaults.guard'), 'description' => 'User account used for system administration.'],
        ];
    }
}
