<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangDatang extends Model
{
  use HasFactory;

  protected $table = 'barang_datangs';
  protected $guarded = [];

  public function pembelian()
  {
    return $this->hasOne(Pembelian::class, 'id_pembelian', 'id_pembelian');
  }

  public function pembelian_detail()
  {
    return $this->hasOne(PembelianDetail::class, 'id_pembelian_detail', 'id_pembelian_detail');
  }
}
