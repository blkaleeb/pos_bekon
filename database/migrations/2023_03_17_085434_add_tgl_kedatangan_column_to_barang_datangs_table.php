<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTglKedatanganColumnToBarangDatangsTable extends Migration
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
                ->date('tgl_kedatangan')
                ->after('selisih')
                ->default(Carbon::now());
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
            $table->dropColumn('tgl_kedatangan');
        });
    }
}
