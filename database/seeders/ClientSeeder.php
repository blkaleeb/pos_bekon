<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('member')->insert(
            [
                [
                    'nama' => 'Halo Cafe',
                    'kode_member' => '00001',
                    'alamat' => 'Jl setiabudi',
                    'telepon' => '0813213246',
                ]
            ],
        );
    }
}
