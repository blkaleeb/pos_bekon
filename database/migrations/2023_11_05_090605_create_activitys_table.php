<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitysTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('activitys', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('barang_dipotong');
      $table
        ->foreign('barang_dipotong')
        ->references('id_produk')
        ->on('produk')
        ->onDelete('cascade');
      $table->unsignedInteger('barang_menjadi');
      $table
        ->foreign('barang_menjadi')
        ->references('id_produk')
        ->on('produk')
        ->onDelete('cascade');
      $table->float('berat_daging', 8, 3);
      $table->float('hasil_daging', 8, 3);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('activitys');
  }
}
