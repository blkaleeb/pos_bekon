<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('purchase_orders', function (Blueprint $table) {
      $table->id();
      $table->string('po_number')->nullable();
      $table->unsignedBigInteger('id_user')->nullable();
      $table
        ->foreign('id_user')
        ->references('id')
        ->on('users')
        ->onDelete('set null');
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
    Schema::dropIfExists('purchase_orders');
  }
}
