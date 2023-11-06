<?php

namespace App\Http\Controllers;

use App\Models\Activitys;
use App\Models\Produk;
use Illuminate\Http\Request;

class ActivitysController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('activity.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $produks = Produk::orderBy('nama_produk', 'asc')->get();
    return view('activity.form', compact('produks'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'barang_dipotong' => 'required|gt:0',
      'barang_menjadi' => 'required|gt:0',
      'berat_daging' => 'required',
      'hasil_daging' => 'required',
    ]);

    $activity = new Activitys();
    $activity->barang_dipotong = $request->barang_dipotong;
    $activity->barang_menjadi = $request->barang_menjadi;
    $activity->berat_daging = $request->berat_daging;
    $activity->hasil_daging = $request->hasil_daging;

    // buat variabel untuk masing-masing produk
    $selisih = $request->berat_daging - $request->hasil_daging;
    $barang_sisa = Produk::where('nama_produk', '=', 'Trimming')->first();
    $produk = Produk::where('id_produk', $request->barang_dipotong)->first();
    $produkJadi = Produk::where('id_produk', $request->barang_menjadi)->first();

    //check apakah barang yang dipotong sama dengan barang yang dihasilkan
    if ($request->barang_dipotong == $request->barang_menjadi) {
      $produk->stok -= $selisih;
    } else {
      $produk->stok -= $request->berat_daging;
      $produkJadi->stok += $request->hasil_daging;
    }

    $barang_sisa->stok += $selisih;
    $barang_sisa->update();
    $produk->update();
    $produkJadi->update();

    $activity->save();

    return view('activity.index')->with('messages', 'Sukses menambahkan aktifitas!');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Activitys  $activitys
   * @return \Illuminate\Http\Response
   */
  public function show(Activitys $activitys)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Activitys  $activitys
   * @return \Illuminate\Http\Response
   */
  public function edit(Activitys $activitys)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Activitys  $activitys
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Activitys $activitys)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Activitys  $activitys
   * @return \Illuminate\Http\Response
   */
  public function destroy(Activitys $activitys)
  {
    //
  }
}
