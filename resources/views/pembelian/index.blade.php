@extends('layouts.master')

@section('title')
  Daftar Pembelian
@endsection

@section('breadcrumb')
  @parent
  <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header with-border">
          <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>
            Transaksi Baru</button>
          @empty(!session('id_pembelian'))
            <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info btn-xs btn-flat">
              <i class="fa fa-pencil"></i> Transaksi Aktif
            </a>
          @endempty
        </div>
        <div class="box-body table-responsive">
          <table class="table-stiped table-bordered table-pembelian table">
            <thead>
              <th width="5%">No</th>
              <th>Tanggal</th>
              <th>Supplier</th>
              {{-- <th>Total Item</th> --}}
              {{-- <th>Diskon</th> --}}
              <th>Inv Real *berdasarkan barang datang</th>
              <th>Invoice Supplier *yang dibayarkan</th>
              <th width="15%"><i class="fa fa-cog"></i></th>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  @includeIf('pembelian.supplier')
  @includeIf('pembelian.detail')
@endsection

@push('scripts')
  <script>
    let table, table1;

    $(function() {
      table = $('.table-pembelian').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
          url: '{{ route('pembelian.data') }}',
        },
        columns: [{
            data: 'DT_RowIndex',
            searchable: false,
            sortable: false
          },
          {
            data: 'tanggal'
          },
          {
            data: 'supplier'
          },
          // {
          //     data: 'total_item'
          // },
          // {
          //     data: 'diskon'
          // },
          {
            //Invoice sesuai barang datang
            data: 'bayar'
          },
          {
            //Invoice sesuai nota supplier
            data: 'total_harga'
          },
          {
            data: 'aksi',
            searchable: false,
            sortable: false
          },
        ]
      });

      $('.table-supplier').DataTable();
      table1 = $('.table-detail').DataTable({
        processing: true,
        bSort: false,
        dom: 'Brt',
        columns: [{
            data: 'DT_RowIndex',
            searchable: false,
            sortable: false
          },
          {
            data: 'nama_produk'
          },
          {
            data: 'jumlah'
          },
          {
            data: 'qty_real'
          },
          {
            data: 'selisih'
          },
        ]
      })
    });

    function addForm() {
      $('#modal-supplier').modal('show');
    }

    function showDetail(url) {
      $('#modal-detail').modal('show');

      table1.ajax.url(url);
      table1.ajax.reload();
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
