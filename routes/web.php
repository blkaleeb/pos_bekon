<?php

use App\Http\Controllers\{
  ActivitysController,
  BarangDatangController,
  DashboardController,
  KategoriController,
  KategoriPengeluaranController,
  LaporanController,
  ProdukController,
  MemberController,
  PengeluaranController,
  PembelianController,
  PembelianDetailController,
  PenjualanController,
  PenjualanDetailController,
  PurchaseOrderController,
  PurchaseOrderDetailController,
  SettingController,
  SupplierController,
  UserController
};
use App\Models\BarangDatang;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  Route::group(['middleware' => 'level:1'], function () {
    Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
    Route::resource('/kategori', KategoriController::class);

    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
    Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
    Route::resource('/produk', ProdukController::class);

    Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
    Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
    Route::resource('/member', MemberController::class);

    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/supplier', SupplierController::class);

    Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
    Route::resource('/pengeluaran', PengeluaranController::class);

    Route::get('/kategori_pengeluaran/data', [KategoriPengeluaranController::class, 'data'])->name(
      'kategori_pengeluaran.data'
    );
    Route::resource('/kategori_pengeluaran', KategoriPengeluaranController::class);

    //Penjualan Super Admin
    Route::get('/penjualan-edit/{id}', [PenjualanDetailController::class, 'editPenjualan'])->name('edit.penjualan');
  });

  Route::group(['middleware' => 'level:1,2'], function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
    Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

    //pembelian
    Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
    Route::get('/pembelian/data2', [PembelianController::class, 'data'])->name('pembelian.data2');
    Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::resource('/pembelian', PembelianController::class)->except('create');

    Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name(
      'pembelian_detail.data'
    );
    Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name(
      'pembelian_detail.load_form'
    );
    Route::resource('/pembelian_detail', PembelianDetailController::class)->except('create', 'show', 'edit');

    //penjualan
    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('/user', UserController::class);

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
    Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
  });

  Route::group(['middleware' => 'level:1,2,3'], function () {
    //purchase order
    Route::get('/purchase_order/data', [PurchaseOrderController::class, 'data'])->name('purchase_order.data');
    Route::resource('/purchase_order', PurchaseOrderController::class);

    Route::get('/purchase_order_detail/{id}/data', [PurchaseOrderDetailController::class, 'data'])->name(
      'purchase_order_detail.data'
    );
    Route::get('/purchase_order_detail/loadform/{diskon}/{total}', [
      PurchaseOrderDetailController::class,
      'loadForm',
    ])->name('purchase_order_detail.load_form');
    Route::resource('/purchase_order_detail', PurchaseOrderDetailController::class)->except('create', 'show', 'edit');

    //transaksi
    Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
    Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
    Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
    Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
    Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

    Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
    Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name(
      'transaksi.load_form'
    );
    Route::resource('/transaksi', PenjualanDetailController::class)->except('create', 'show', 'edit');

    //penjualan
    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::get('/penjualan/edit/{id}', [PenjualanController::class, 'editform'])->name('penjualan.editform');
    Route::put('/penjualan/edit/{id}', [PenjualanController::class, 'changeStatus'])->name('penjualan.changeStatus');

    //pengeluaran wallet
    Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
    Route::resource('/pengeluaran', PengeluaranController::class);

    //check barang datang
    Route::resource('/barang_datang', BarangDatangController::class);

    //access pembelian kecuali tambah pembelian
    Route::resource('/pembelian', PembelianController::class)->except('create');
    Route::get('/pembelian-confirm', [PembelianController::class, 'listConfirm'])->name('pembelian.listConfirm');
    Route::get('/pembelian/data2', [PembelianController::class, 'data2'])->name('pembelian.data2');

    //update profile
    Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');

    Route::resource('/activitys', ActivitysController::class);
  });
});
