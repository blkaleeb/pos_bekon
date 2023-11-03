<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubtotalRealToBarangDatangsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('barang_datangs', function (Blueprint $table) {
      $table
        ->integer('subtotal_real')
        ->after('selisih')
        ->default(0)
        ->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('barang_datangs', function (Blueprint $table) {
      $table->dropColumn('subtotal_real');
    });
  }
}
