@extends('layouts.master')

@section('title')
  Dashboard
@endsection

@section('breadcrumb')
  @parent
  <li class="active">Dashboard</li>
@endsection

@section('content')
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{ $kategori }}</h3>

          <p>Total Kategori</p>
        </div>
        <div class="icon">
          <i class="fa fa-cube"></i>
        </div>
        <a href="{{ route('kategori.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $produk }}</h3>

          <p>Total Produk</p>
        </div>
        <div class="icon">
          <i class="fa fa-cubes"></i>
        </div>
        <a href="{{ route('produk.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ $member }}</h3>

          <p>Total Member</p>
        </div>
        <div class="icon">
          <i class="fa fa-id-card"></i>
        </div>
        <a href="{{ route('member.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ $supplier }}</h3>

          <p>Total Supplier</p>
        </div>
        <div class="icon">
          <i class="fa fa-truck"></i>
        </div>
        <a href="{{ route('supplier.index') }}" class="small-box-footer">Lihat <i
            class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
  <!-- /.row -->
  <!-- Main row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Grafik Penjualan {{ tanggal_indonesia($tanggal_awal, false) }} s/d
            {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="chart">
                <!-- Sales Chart Canvas -->
                <canvas id="salesChart" style="height: 180px;"></canvas>
              </div>
              <!-- /.chart-responsive -->
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <strong>Total penjualan:</strong> Rp {{ format_uang($total_pendapatan) }} <br />
              <strong>Total Quantity terjual:</strong> {{ format_qty($total_qty) }} kg
            </div>
          </div>
          <!-- /.row -->
        </div>
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header with-border">
          <h2 class="text-center">
            KETERSEDIAAN BARANG
          </h2>
        </div>
        <div class="box-body text-center">
          <table class="table-striped table-bordered table">
            <thead>
              <th width="5%">No</th>
              <th>Nama produk</th>
              <th>Stok</th>
            </thead>
            <tbody>
              @foreach ($products as $key => $product)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td class="text-left">{{ $product->nama_produk }}</td>
                  <td>{{ $product->nama_produk == 'Bacon' ? intval($product->stok) . ' pack' : $product->stok }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row (main row) -->
@endsection

@push('scripts')
  <!-- ChartJS -->
  <script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
  <script>
    $(function() {
      // Get context with jQuery - using jQuery's .get() method.
      var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
      // This will get the first returned node in the jQuery collection.
      var salesChart = new Chart(salesChartCanvas);

      var salesChartData = {
        labels: {{ json_encode($data_tanggal) }},
        datasets: [{
          label: 'Pendapatan',
          fillColor: 'rgba(60,141,188,0.9)',
          strokeColor: 'rgba(60,141,188,0.8)',
          pointColor: '#3b8bba',
          pointStrokeColor: 'rgba(60,141,188,1)',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: {{ json_encode($data_pendapatan) }}
        }]
      };

      var salesChartOptions = {
        pointDot: false,
        responsive: true
      };

      salesChart.Line(salesChartData, salesChartOptions);
    });
  </script>

  {{-- Table JS --}}
  <script>
    let table;
    $(function() {
      table = $('.table').DataTable({
        responsive: true,
        processing: true,
        serverSide: false,
        autoWidth: false,
      })
    })
  </script>
@endpush
