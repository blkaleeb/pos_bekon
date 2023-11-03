<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelisihColumnToBarangDatangsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('barang_datangs', function (Blueprint $table) {
      $table->float('selisih', 8, 3)->after('qty_real')->nullable;
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
      $table->dropColumn('selisih');
    });
  }
}
