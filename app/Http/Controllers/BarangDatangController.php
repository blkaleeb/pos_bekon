<?php

namespace App\Http\Controllers;

use App\Models\BarangDatang;
use App\Models\PembelianDetail;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BarangDatang  $barangDatang
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        
        $pembelian_detail = PembelianDetail::with('pembelian','produk')->where('id_pembelian', $request->barang_datang)->get();

        $supplier = $pembelian_detail[0]->pembelian->supplier->nama;

        return view('barang_datang.form', compact('pembelian_detail', 'supplier'));
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
    public function update(Request $request, BarangDatang $barangDatang)
    {
        //
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
