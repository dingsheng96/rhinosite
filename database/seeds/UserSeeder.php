<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create super admin
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@email.com'],
            [
                'name' => 'Super Admin',
                'mobile_no' => null,
                'tel_no' => null,
                'reg_no' => null,
                'password' => Hash::make('password'),
                'status' => User::STATUS_ACTIVE,
            ]
        );

        $superadmin->assignRole(Role::ROLE_SUPER_ADMIN);
    }
}
