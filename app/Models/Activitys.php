<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activitys extends Model
{
  use HasFactory;

  protected $table = 'activitys';
  protected $guarded = [];

  public function barang_dipotong()
  {
    return $this->hasOne(Produk::class, 'id_produk', 'barang_dipotong');
  }
  public function barang_menjadi()
  {
    return $this->hasOne(Produk::class, 'id_produk', 'barang_menjadi');
  }
}
