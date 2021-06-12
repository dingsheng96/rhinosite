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
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@email.com'],
            [
                'name' => 'Super Admin',
                'mobile_no' => '60123456789',
                'tel_no' => null,
                'reg_no' => '1234567890',
                'password' => Hash::make('password'),
                'status' => User::STATUS_ACTIVE,
            ]
        );

        $superadmin->assignRole(Role::ROLE_SUPER_ADMIN);
    }
}
