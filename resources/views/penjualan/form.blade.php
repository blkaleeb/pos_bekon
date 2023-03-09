<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" onclick="hapusdata()" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit penjualan</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 justify-end">
                        <div class="">
                            <h3>Nama: </h3>
                            <label id="nama" for="nama">Nama</label>
                        </div>
                        <div class="">
                            <h3>Tanggal: </h3>
                            <label id="tgl" for="tgl">Tgl Pembelian</label>
                        </div>
                        <div class="">
                            <h3>Total: </h3>
                            <label id="total" for="total">Total Pembelian</label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <form action="" method="post">
                            @csrf
                            @method('post')
                            <div class="form-group">
                                <label for="statuses" class="col-lg-2 col-lg-offset-1 control-label">Status</label>
                                <div class="col-lg-6">
                                    <select class="form-control" name="statuses" id="statuses">
                                        @foreach ($statuses as $key => $item)
                                            <option value="{{ $key }}">{{ $item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
