<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class KategoriPengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kategori_pengeluaran.index');
    }

    public function data()
    {
        $kategori = KategoriPengeluaran::orderBy('id', 'desc')->get();

        return datatables()
            ->of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('kategori.update', $kategori->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('kategori.destroy', $kategori->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
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
        $kategori = new KategoriPengeluaran();
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->jenis = $request->jenis;
        $kategori->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KategoriPengeluaran  $kategoriPengeluaran
     * @return \Illuminate\Http\Response
     */
    public function show(KategoriPengeluaran $kategoriPengeluaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KategoriPengeluaran  $kategoriPengeluaran
     * @return \Illuminate\Http\Response
     */
    public function edit(KategoriPengeluaran $kategoriPengeluaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KategoriPengeluaran  $kategoriPengeluaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KategoriPengeluaran $kategoriPengeluaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KategoriPengeluaran  $kategoriPengeluaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriPengeluaran $kategoriPengeluaran)
    {
        //
    }
}
