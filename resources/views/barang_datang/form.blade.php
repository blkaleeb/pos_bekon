@extends('layouts.master')

@section('title')
    Datang barang
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Datang barang</li>
@endsection

@section('content')
    <section class="invoice">
        <form action="{{ route('barang_datang.store') }}" method="post">
            @csrf
            <input type="hidden" name="id_pembelian" value="{{ $pembelian->id_pembelian }}">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> Bekon Bandung
                        <small class="pull-right">Date:
                            {{ tanggal_indonesia($pembelian->created_at) }}
                        </small>
                    </h2>
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    Supplier
                    <address>
                        <strong>{{ $supplier->nama }}</strong><br>
                        {{ $supplier->alamat }}<br>
                        Phone: {{ $supplier->phone }}<br>
                    </address>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Order Qty</th>
                                <th>Qty Real</th>
                                <th>Selisih</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($pembelian_details as $key => $item)
                                <input type="hidden" name="id_pembelian_detail[{{ $item->id_pembelian_detail }}]"
                                    value="{{ $item->id_pembelian_detail }}">
                                <tr>
                                    <td>{{ $item->produk->nama_produk }}</td>
                                    <td width=25% id='order_qty[{{ $item->id_pembelian_detail }}]'>{{ $item->jumlah }}</td>
                                    <td width=25%>
                                        <input class="form-control" type="number"
                                            name="qty_real[{{ $item->id_pembelian_detail }}]"
                                            id="qty_real[{{ $item->id_pembelian_detail }}]">
                                    </td>
                                    <td>
                                        <div class="" id="selisih[{{ $item->id_pembelian_detail }}]"></div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="row no-print">
                <div class="col-xs-12">
                    {{-- <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a> --}}
                    <button type="submit" class="btn btn-success pull-right">
                        Update Barang
                    </button>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        const qtyReals = document.querySelectorAll('[name^="qty_real"]');
        qtyReals.forEach(qtyReal => {
            qtyReal.addEventListener('input', () => {
                const key = qtyReal.getAttribute('name').match(/\[(\d+)\]/)[1];
                const orderQty = parseFloat(document.querySelector(`#order_qty\\[${key}\\]`).textContent
                    .trim());
                const qtyRealValue = parseFloat(qtyReal.value.trim());
                const selisih = qtyRealValue - orderQty;
                const selisihElem = document.querySelector(`#selisih\\[${key}\\]`);
                selisihElem.textContent = selisih;
            });
        });
    </script>
@endpush
