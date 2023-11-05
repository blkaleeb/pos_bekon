@extends('layouts.master')

@section('title')
  Pilih aktivitas
@endsection

@section('breadcrumb')
  @parent
  <li class="active">Daftar Aktifitas</li>
@endsection

@section('content')
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
