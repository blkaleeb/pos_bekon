<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangDatangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_datangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_pembelian')->nullable();
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian');
            $table->unsignedInteger('id_pembelian_detail')->nullable();
            $table->foreign('id_pembelian_detail')->references('id_pembelian_detail')->on('pembelian_detail');
            $table->string('qty_real');
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
        Schema::dropIfExists('barang_datangs');
    }
}
