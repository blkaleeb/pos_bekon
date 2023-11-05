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
    $produks = Produk::get();
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
    // dd($request);
    $validatedData = $request->validate([
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
    $activity->save();
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
