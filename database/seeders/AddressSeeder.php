<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->insert([
            'street'   => "Rua Alameda",
            'number'   => 150,
            'city'  => "Valaquia",
            'cep'   => "58035-140",
            'user_id' => 1
        ]);
    }
}
