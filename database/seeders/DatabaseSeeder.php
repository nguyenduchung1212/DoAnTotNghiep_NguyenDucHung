<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $dataRole = [
            [
                'id' => 1,
                'name' => 'manager',
            ],
            [
                'id' => 2,
                'name' => 'admin',
            ],
            [
                'id' => 3,
                'name' => 'user',
            ],
        ];
        DB::table('roles')->insert($dataRole);

        $dataUser = [
            [
                'id' => 1,
                'name' => Str::random(10),
                'email' => 'manager@gmail.com',
                'password' => Hash::make('123456'),
                'username'=> 'manager',
                'phone'=> '123',
                'role_id' => 1,
            ],
            [
                'id' => 2,
                'name' => Str::random(10),
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'username'=> 'admin',
                'phone'=> '234',
                'role_id' => 2,
            ],
            [
                'id' => 3,
                'name' => Str::random(10),
                'email' => 'user@gmail.com',
                'password' => Hash::make('123456'),
                'username'=> 'user',
                'phone'=> '345',
                'role_id' => 3,
            ],
        ];
        DB::table('users')->insert($dataUser);
    }
}
