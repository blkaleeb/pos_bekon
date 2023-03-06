<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Wallet;

class PengeluaranController extends Controller
{
    public function index()
    {
        $this->data['kategori'] = KategoriPengeluaran::all();

        $this->data['wallet'] = Wallet::latest()->first();

        return view('pengeluaran.index', $this->data);
    }

    public function data()
    {
        // $pengeluaran = Pengeluaran::orderBy('id_pengeluaran', 'desc')->get();

        $pengeluaran = Pengeluaran::leftJoin('kategori_pengeluarans', 'kategori_pengeluarans.id', 'pengeluaran.id_kategori')
            ->select('pengeluaran.*', 'nama_kategori')
            ->get();

        return datatables()
            ->of($pengeluaran)
            ->addIndexColumn()
            ->addColumn('created_at', function ($pengeluaran) {
                return tanggal_indonesia($pengeluaran->created_at, false);
            })
            ->addColumn('nominal', function ($pengeluaran) {
                return format_uang($pengeluaran->nominal);
            })
            ->addColumn('aksi', function ($pengeluaran) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('pengeluaran.update', $pengeluaran->id_pengeluaran) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('pengeluaran.destroy', $pengeluaran->id_pengeluaran) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $pengeluaran = new Pengeluaran();
        $pengeluaran->id_kategori = $request->id_kategori;
        $pengeluaran->deskripsi = $request->deskripsi;
        $pengeluaran->nominal = $request->nominal;
        $pengeluaran->save();

        $kategoripengeluaran = KategoriPengeluaran::find($request->id_kategori);
        $jenispengeluaran = $kategoripengeluaran->jenis;

        $wallets = new Wallet();
        $pengeluaranBaru = Pengeluaran::latest()->first();

        if($jenispengeluaran == 'credit') {
            $wallets->debit = 0;
            $wallets->credit = $request->nominal;
            $wallets->id_pengeluaran = $pengeluaranBaru->id_pengeluaran;
            $wallets->saldo = $request->saldo + $wallets->credit - $wallets->debit;
            $wallets->save();
        } else if ($jenispengeluaran == 'debit') {
            $wallets->credit = 0;
            $wallets->debit = $request->nominal;
            $wallets->id_pengeluaran = $pengeluaranBaru->id_pengeluaran;
            $wallets->saldo = $request->saldo + $wallets->credit - $wallets->debit;
            $wallets->save();
        }

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pengeluaran = Pengeluaran::find($id);

        return response()->json($pengeluaran);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::find($id)->delete();

        return response(null, 204);
    }
}
