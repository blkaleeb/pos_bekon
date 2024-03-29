<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\SalesMember;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
  public function __construct()
  {
    $this->data['jenis_pembayaran'] = Penjualan::jenis_pembayaran();
    $this->data['statuses'] = Penjualan::statuses();
  }

  public function index()
  {
    $produk = Produk::orderBy('nama_produk')->get();
    $member = Member::orderBy('nama')->get();
    $salesMembers = SalesMember::orderBy('nama')->get();
    $diskon = Setting::first()->diskon ?? 0;

    $paymentMethods = Penjualan::jenis_pembayaran();

    // Cek apakah ada transaksi yang sedang berjalan
    if ($id_penjualan = session('id_penjualan')) {
      $penjualan = Penjualan::find($id_penjualan);
      if (!$penjualan) {
        return redirect()->route('transaksi.baru');
      }
      $memberSelected = $penjualan->member ?? new Member();

      return view(
        'penjualan_detail.index',
        compact(
          'produk',
          'member',
          'diskon',
          'id_penjualan',
          'penjualan',
          'memberSelected',
          'paymentMethods',
          'salesMembers'
        )
      );
    } else {
      if (auth()->user()->level == 1) {
        return redirect()->route('transaksi.baru');
      } else {
        return redirect()->route('home');
      }
    }
  }

  public function data($id)
  {
    $detail = PenjualanDetail::with('produk')
      ->where('id_penjualan', $id)
      ->get();

    $data = [];
    $total = 0;
    $total_item = 0;

    foreach ($detail as $item) {
      $row = [];
      $row['kode_produk'] = '<span class="label label-success">' . $item->produk['kode_produk'] . '</span>';
      $row['nama_produk'] = $item->produk['nama_produk'];
      $row['harga_jual'] =
        '<input type="number" class="form-control input-sm harga_jual" data-id="' .
        $item->id_penjualan_detail .
        '" value="' .
        format_uang($item->harga_jual) .
        '">';
      $row['jumlah'] =
        '<input type="number" class="form-control input-sm quantity" data-id="' .
        $item->id_penjualan_detail .
        '" value="' .
        $item->jumlah .
        '">';
      $row['diskon'] = format_uang($item->diskon);
      $row['subtotal'] = '<span id="subtotal"> Rp. ' . format_uang($item->subtotal) . '</span>';
      $row['aksi'] =
        '<div class="btn-group">
                                    <button onclick="deleteData(`' .
        route('transaksi.destroy', $item->id_penjualan_detail) .
        '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
      $data[] = $row;

      $total += $item->harga_jual * $item->jumlah - $item->diskon;
      $total_item += $item->jumlah;
    }
    $data[] = [
      'kode_produk' =>
        '
                <div class="total hide">' .
        $total .
        '</div>
                <div class="total_item hide">' .
        $total_item .
        '</div>',
      'nama_produk' => '',
      'harga_jual' => '',
      'jumlah' => '',
      'diskon' => '',
      'subtotal' => '',
      'aksi' => '',
    ];

    return datatables()
      ->of($data)
      ->addIndexColumn()
      ->rawColumns(['aksi', 'kode_produk', 'harga_jual', 'jumlah', 'subtotal'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $produk = Produk::where('id_produk', $request->id_produk)->first();
    if (!$produk) {
      return response()->json('Data gagal disimpan', 400);
    }

    $detail = new PenjualanDetail();
    $detail->id_penjualan = $request->id_penjualan;
    $detail->id_produk = $produk->id_produk;
    $detail->harga_jual = $produk->harga_jual;
    $detail->jumlah = 1;
    $detail->diskon = $produk->diskon;
    $detail->subtotal = $produk->harga_jual - $produk->diskon;
    $detail->save();

    return response()->json('Data berhasil disimpan', 200);
  }

  public function update(Request $request, $id)
  {
    $detail = PenjualanDetail::find($id);
    if ($request->jumlah) {
      $detail->jumlah = $request->jumlah;
      $detail->subtotal = $detail->harga_jual * $detail->jumlah - $detail->diskon;
      $detail->update();
    } elseif ($request->harga_jual) {
      $detail->harga_jual = $request->harga_jual;
      $detail->subtotal = $detail->harga_jual * $detail->jumlah - $detail->diskon;
      $detail->update();
    }
  }

  public function destroy($id)
  {
    $detail = PenjualanDetail::find($id);
    $detail->delete();

    return response(null, 204);
  }

  public function loadForm($diskon = 0, $total = 0, $diterima = 0)
  {
    $bayar = $total - $diskon;
    $kembali = $diterima != 0 ? $diterima - $bayar : 0;
    $data = [
      'totalrp' => format_uang($total),
      'bayar' => $bayar,
      'bayarrp' => format_uang($bayar),
      'terbilang' => ucwords(terbilang($bayar) . ' Rupiah'),
      'kembalirp' => format_uang($kembali),
      'kembali_terbilang' => ucwords(terbilang($kembali) . ' Rupiah'),
    ];

    return response()->json($data);
  }

  public function editPenjualan($id)
  {
    $produk = Produk::orderBy('nama_produk')->get();
    $member = Member::orderBy('nama')->get();
    $salesMembers = SalesMember::orderBy('nama')->get();
    $diskon = Setting::first()->diskon ?? 0;
    $paymentMethods = Penjualan::jenis_pembayaran();
    $id_penjualan = $id;
    $penjualan = Penjualan::find($id);
    $penjualan_payment = $penjualan->jenis_pembayaran;
    $memberSelected = $penjualan->member ?? new Member();

    return view(
      'penjualan_detail.edit',
      compact(
        'produk',
        'member',
        'diskon',
        'id_penjualan',
        'penjualan',
        'memberSelected',
        'paymentMethods',
        'penjualan_payment',
        'salesMembers'
      )
    );
  }
}
