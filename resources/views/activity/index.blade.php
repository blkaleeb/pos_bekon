@extends('layouts.master')

@section('title')
  Pilih aktivitas
@endsection

@section('breadcrumb')
  @parent
  <li class="active">Daftar Aktifitas</li>
@endsection

@section('content')
  @if (!empty($messages))
    <div class="callout callout-success">
      <h4>{{ $messages }}</h4>
    </div>
  @endif
  <div class="row">
    <div class="col-lg-4">
      <a href="{{ route('activitys.create') }}">
        <div class="box">
          <div class="box-header">
            <div class="text-center">
              SLICE
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  {{-- @includeIf('penjualan.detail')
  @includeIf('penjualan.form') --}}
@endsection
