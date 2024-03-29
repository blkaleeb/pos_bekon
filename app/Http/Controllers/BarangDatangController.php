<?php

namespace App\Http\Controllers;

use App\Models\BarangDatang;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangDatangController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('barang_datang.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // foreach ($request->id_pembelian_detail as $key => $item) {
    //     $barang_datang = new BarangDatang();
    //     $barang_datang->id_pembelian = $request->id_pembelian;
    //     $barang_datang->id_pembelian_detail = $item;
    //     $barang_datang->qty_real = $request->qty_real[$key];
    //     $barang_datang->save();
    // }

    // return view("pembelian.index", [
    //     "success" => "Berhasil update barang datang!",
    // ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\BarangDatang  $barangDatang
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request)
  {
    $pembelian_details = PembelianDetail::with('pembelian', 'produk')
      ->where('id_pembelian', $request->barang_datang)
      ->get();

    $barang_datangs = BarangDatang::with('pembelian', 'pembelian_detail.produk')
      ->where('id_pembelian', $request->barang_datang)
      ->get();

    $pembelian = Pembelian::with('supplier')
      ->where('id_pembelian', $request->barang_datang)
      ->first();
    $supplier = $pembelian_details[0]->pembelian->supplier;

    return view('barang_datang.form', compact('pembelian_details', 'supplier', 'pembelian', 'barang_datangs'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\BarangDatang  $barangDatang
   * @return \Illuminate\Http\Response
   */
  public function edit(BarangDatang $barangDatang)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\BarangDatang  $barangDatang
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $totalreal = 0;
    foreach ($request->id_pembelian_detail as $key => $item) {
      $barang_datang = BarangDatang::with('pembelian_detail')
        ->where('id_pembelian_detail', $item)
        ->first();

      // Ambil value qty real
      $qty_real = $barang_datang->qty_real;
      $barang_datang->qty_real = $request->qty_real[$key];
      $barang_datang->subtotal_real = $barang_datang->qty_real * $barang_datang->pembelian_detail->harga_beli;
      $barang_datang->selisih = $request->selisih[$key];
      $barang_datang->update();

      $idproduk = $barang_datang->pembelian_detail->id_produk;
      $produk = Produk::find($idproduk);
      if ($request->qty_real[$key] > $qty_real) {
        $jumlah = $request->qty_real[$key] - $qty_real;
        $produk->stok += $jumlah;
      } elseif ($request->qty_real[$key] < $qty_real) {
        $jumlah = $qty_real - $request->qty_real[$key];
        $produk->stok -= $jumlah;
      }

      $produk->update();
    }

    $subtotal_real = BarangDatang::with('pembelian_detail')
      ->where('id_pembelian', $request->id_pembelian)
      ->sum('subtotal_real');
    $pembelian = Pembelian::find($request->id_pembelian);
    $pembelian->bayar = $subtotal_real;
    $pembelian->update();

    $supplier = Supplier::orderBy('nama')->get();

    if (Auth::user()->level == 1) {
      return view('pembelian.index', [
        'success' => 'Berhasil update barang datang!',
        'supplier' => $supplier,
      ]);
    } else {
      return view('pembelian.listConfirm.index', [
        'success' => 'Berhasil update barang datang!',
        'supplier' => $supplier,
      ]);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\BarangDatang  $barangDatang
   * @return \Illuminate\Http\Response
   */
  public function destroy(BarangDatang $barangDatang)
  {
    //
  }
}
