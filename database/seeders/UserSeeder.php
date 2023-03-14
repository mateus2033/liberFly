<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'name' => "Administrador",
            'email' => "admin@admin.com",
            'age'  => 500,
            'cpf'  => "761.234.990-68",
            'permission_id' => 1,
            'password' => Hash::make('12345678')
        ]);
    }
}
