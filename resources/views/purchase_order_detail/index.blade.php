@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }

        .table-pembelian tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <table>
                        <tr>
                            <td>Purchasing: </td>
                            <td>: {{ $purchase_order->po_number }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">

                    <form class="form-produk">
                        @csrf
                        <div class="form-group row">
                            <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_purchase_order" id="id_purchase_order"
                                        value="{{ $purchase_order_id }}">
                                    <input type="hidden" name="id_produk" id="id_produk">
                                    <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                    <span class="input-group-btn">
                                        <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i
                                                class="fa fa-arrow-right"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-pembelian">
                        <thead>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th width="15%">Jumlah</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>

                <div class="box-footer">
                    <a href="{{ route('purchase_order.index') }}" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i
                            class="fa fa-floppy-o"></i> Selesai</a>
                </div>
            </div>
        </div>
    </div>

    @includeIf('purchase_order_detail.produk')
@endsection

@push('scripts')
    <script>
        let table, table2;

        $(function() {
            $('body').addClass('sidebar-collapse');

            table = $('.table-pembelian').DataTable({
                    responsive: true,
                    // processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('purchase_order_detail.data', $purchase_order_id) }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            searchable: false,
                            sortable: false
                        },
                        {
                            data: 'kode_produk'
                        },
                        {
                            data: 'nama_produk'
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                    paginate: false
                })
                .on('draw.dt', function() {
                    loadForm();
                });
            table2 = $('.table-produk').DataTable();

            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let jumlah = parseFloat($(this).val());

                if (jumlah > 10000) {
                    $(this).val(10000);
                    alert('Jumlah tidak boleh lebih dari 10000');
                    return;
                }

                $.post(`{{ url('/purchase_order_detail') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah
                    })
                    .done(response => {
                        alert('sukses menyimpan data!');
                        table.ajax.reload();
                    })
                    .fail(errors => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            });

            $('.btn-simpan').on('click', function() {
                $('.form-pembelian').submit();
            });
        });

        function tampilProduk() {
            $('#modal-produk').modal('show');
        }

        function hideProduk() {
            $('#modal-produk').modal('hide');
        }

        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            hideProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post('{{ route('purchase_order_detail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_produk').focus();
                    table.ajax.reload();
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }
    </script>
@endpush
