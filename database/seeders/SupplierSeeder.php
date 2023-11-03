<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('supplier')->insert([
      [
        'nama' => 'Pande Yayang',
        'alamat' => 'Jl setiabudi',
        'telepon' => '0813213246',
      ],
      [
        'nama' => 'Harsa',
        'alamat' => 'Jl setiabudi',
        'telepon' => '0813213246',
      ],
    ]);
  }
}
