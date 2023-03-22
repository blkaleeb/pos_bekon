<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase_order.index');
    }

    public function data()
    {
        $purchase_order = PurchaseOrder::orderBy('id', 'desc')->get();

        return datatables()
            ->of($purchase_order)
            ->addIndexColumn()
            ->addColumn('po_number', function ($purchase_order) {
                return $purchase_order->po_number;
            })
            ->editColumn('created_at', function ($purchase_order) {
                return tanggal_indonesia($purchase_order->created_at);
            })
            ->addColumn('aksi', function ($purchase_order) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('purchase_order.show', $purchase_order->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('purchase_order.destroy', $purchase_order->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $purchase_order = new PurchaseOrder();
        $purchase_order->po_number  = generatePoNumber();
        $purchase_order->id_user = auth()->id();
        $purchase_order->save();

        session(['purchase_order_id' => $purchase_order->id]);

        return redirect()->route('purchase_order_detail.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $purchase_order)
    {
        $detail = PurchaseOrderDetail::with('produk')->where('id_purchase_order', $purchase_order)->get();
        // dd($detail);

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('qty', function ($detail) {
                return $detail->qty;
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();

        return response('Berhasil menghapus PO', 204);
    }
}
