<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_purchase_order');
            $table
                ->foreign('id_purchase_order')
                ->references('id')
                ->on('purchase_orders')
                ->onDelete('cascade');
            $table->unsignedInteger('id_produk')->nullable();
            $table
                ->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('set null');
            $table->float('qty', 8, 3);
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
        Schema::dropIfExists('purchase_order_details');
    }
}
