<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriPengeluaranToPengeluaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kategori')
                  ->after('id_pengeluaran');
            $table->foreign('id_kategori')->references('id')->on('kategori_pengeluarans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropForeign('pengeluaran_id_kategori_foreign');
            $table->dropColumn('kode_produk');
        });
    }
}
