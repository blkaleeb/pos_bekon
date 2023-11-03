<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisPembayaranColumnToPenjualanTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('penjualan', function (Blueprint $table) {
      $table
        ->tinyInteger('jenis_pembayaran')
        ->default(1)
        ->after('bayar');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('penjualan', function (Blueprint $table) {
      $table->dropColumn('jenis_pembayaran');
    });
  }
}
