@extends('layouts.master')

@section('title')
  Slice/Bone Saw Daging
@endsection

@section('breadcrumb')
  @parent
  <li class="active">Slice/Bone Saw Daging</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-4">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Form Slice Daging</h3>
        </div>
        <form action="{{ route('activitys.store') }}" class="form-penjualan" method="post">
          @csrf
          <div class="box-body">
            <div class="form-group @error('barang_dipotong') has-error @enderror">
              <label for="barang_dipotong">Daging</label>
              <select class="form-control" name="barang_dipotong" id="barang_dipotong">
                @if (old('barang_dipotong'))
                  <option value={{ old('barang_dipotong') }} selected>{{ old('barang_dipotong') }}</option>
                @else
                  <option value="0" selected disabled>-- Pilih Daging yang ingin di Slice --</option>
                @endif
                @foreach ($produks as $key => $item)
                  <option value="{{ $item->id_produk }}">{{ $item->nama_produk }}</option>
                @endforeach
              </select>
              @error('barang_dipotong')
                <span class="help-block">Daging harus dipilih!</span>
              @enderror
            </div>
            <div class="form-group @error('barang_menjadi') has-error @enderror">
              <label for="barang_menjadi">Hasil Daging</label>
              <select class="form-control" name="barang_menjadi" id="barang_menjadi">
                @if (old('barang_menjadi'))
                  <option value={{ old('barang_menjadi') }} selected>{{ old('barang_menjadi') }}</option>
                @else
                  <option value="0" selected disabled>-- Pilih Hasil Daging --</option>
                @endif
                @foreach ($produks as $key => $item)
                  <option value="{{ $item->id_produk }}">{{ $item->nama_produk }}</option>
                @endforeach
              </select>
              @error('barang_menjadi')
                <span class="help-block">Hasil Daging harus dipilih!</span>
              @enderror
            </div>
            <div class="form-group @error('berat_daging') has-error @enderror">
              <label for="berat_daging">Berat Daging</label>
              <input type="number" step='0.001' class="form-control" name="berat_daging" id="berat_daging"
                placeholder="Masukkan berat daging.."
                @if (old('berat_daging')) value="{{ old('berat_daging') }}" @endif>
              @error('berat_daging')
                <span class="help-block">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group @error('hasil_daging') has-error @enderror">
              <label for="hasil_daging">Hasil Daging</label>
              <input type="number" step='0.001' class="form-control" name="hasil_daging" id="hasil_daging"
                placeholder="Masukkan hasil slice.."
                @if (old('berat_daging')) value="{{ old('hasil_daging') }}" @endif>
              @error('hasil_daging')
                <span class="help-block">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- @includeIf('penjualan.detail')
  @includeIf('penjualan.form') --}}
@endsection
