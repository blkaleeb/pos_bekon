<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
  use HasFactory;

  protected $table = 'purchase_order_details';
  protected $guarded = [];

  public function produk()
  {
    return $this->hasOne(Produk::class, 'id_produk', 'id_produk');
  }
}
