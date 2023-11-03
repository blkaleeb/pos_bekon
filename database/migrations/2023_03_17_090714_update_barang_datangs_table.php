<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBarangDatangsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('barang_datangs', function (Blueprint $table) {
      $table->dropForeign(['id_pembelian_detail']); // Drop the existing foreign key
      $table
        ->foreign('id_pembelian_detail')
        ->references('id_pembelian_detail')
        ->on('pembelian_detail')
        ->onDelete('cascade'); // Add the onDelete cascade
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
      $table->dropForeign(['id_pembelian_detail']);
      $table
        ->foreign('id_pembelian_detail')
        ->references('id_pembelian_detail')
        ->on('pembelian_detail');
    });
  }
}
