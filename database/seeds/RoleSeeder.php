<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE TABLE ' . (new Role())->getTable());

        foreach ($this->getData() as $data) {
            Role::create([
                'name' => $data['name'],
                'guard_name' => $data['guard_name'],
                'description' => $data['description']
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    private function getData()
    {
        return [
            ['name' => 'Super Admin', 'guard_name' => User::TYPE_ADMIN, 'description' => 'User account used for system administration.'],
        ];
    }
}
