<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

use function PHPUnit\Framework\isNull;

class PenjualanController extends Controller
{
    public function __construct()
    {
        $this->data['jenis_pembayaran'] = Penjualan::jenis_pembayaran();
        $this->data['statuses'] = Penjualan::statuses();
    }

    public function index()
    {
        return view('penjualan.index', $this->data);
    }

    public function data()
    {
        $penjualan = Penjualan::with('member', 'sales')->orderBy('id_penjualan', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item', function ($penjualan) {
                return format_qty($penjualan->total_item);
            })
            ->addColumn('id_salesmember', function ($penjualan) {
                return $penjualan->sales->nama;
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_harga);
            })
            ->addColumn('diterima', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->diterima);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('kode_member', function ($penjualan) {
                $member = $penjualan->member->nama ?? '';
                return '<span class="label label-success">'. $member .'</spa>';
            })
            ->editColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->editColumn('jenis_pembayaran', function ($penjualan) {
                return display_payment_method($penjualan->jenis_pembayaran);
            })
            ->editColumn('statuses', function ($penjualan) {
                return display_statuses($penjualan->statuses);
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('penjualan.editform', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        $newmember = null;
        if($request->id_member == null && $request->kode_member != null) {
            $member = Member::latest()->first() ?? new Member();
            $kode_member = (int) $member->kode_member +1;

            $member = new Member();
            $member->kode_member = tambah_nol_didepan($kode_member, 5);
            $member->nama = $request->kode_member;
            $member->telepon = "";
            $member->alamat = "";
            $member->save();
            $newmember = $member->id_member;
        }

        $memberid = !$request->id_member ? $newmember : $request->id_member;

        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->id_member = $memberid;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->jenis_pembayaran = $request->jenis_pembayaran;
        $penjualan->id_salesmember = $request->id_salesmember;
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();

            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
        }

        return redirect()->route('transaksi.selesai');
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_qty($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('penjualan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    public function editform($id){
        $penjualan = Penjualan::with('member')->find($id);
        $date = Carbon::parse($penjualan->created_at)->format('d F Y');
        $penjualan->tanggal = $date;

        return response()->json($penjualan);
    }

    public function changeStatus(Request $request, $id){
        $penjualan = Penjualan::find($id);
        $penjualan->statuses = $request->statuses;
        $penjualan->update();

        return response()->json('Status telah terupdate', 200);
    }
}
