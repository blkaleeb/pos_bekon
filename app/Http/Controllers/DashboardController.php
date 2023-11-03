<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
    $kategori = Kategori::count();
    $produk = Produk::count();
    $supplier = Supplier::count();
    $member = Member::count();

    $tanggal_awal = date('Y-m-01');
    $tanggal_akhir = date('Y-m-d');

    $data_tanggal = [];
    $data_pendapatan = [];

    $total_pendapatan = 0;
    $total_qty = 0;

    while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
      $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

      $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
      $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
      $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

      $qty = PenjualanDetail::where('created_at', 'LIKE', "%$tanggal_awal%")
        ->where('id_produk', '!=', 4)
        ->sum('jumlah');
      $qty_bacon = PenjualanDetail::where('created_at', 'LIKE', "%$tanggal_awal%")
        ->where('id_produk', '=', 4)
        ->sum('jumlah');
      $qty_bacon = $qty_bacon / 4;

      $penjualan = $total_penjualan;
      $data_pendapatan[] += $penjualan;
      $total_pendapatan += $penjualan;
      $total_qty += $qty + $qty_bacon;

      $tanggal_awal = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_awal)));
    }

    $tanggal_awal = date('Y-m-01');

    $products = Produk::all();

    if (auth()->user()->level == 1) {
      return view(
        'admin.dashboard',
        compact(
          'kategori',
          'produk',
          'supplier',
          'member',
          'tanggal_awal',
          'tanggal_akhir',
          'data_tanggal',
          'data_pendapatan',
          'products',
          'total_pendapatan',
          'total_qty'
        )
      );
    } else {
      return view('kasir.dashboard', compact('products'));
    }
  }
}
