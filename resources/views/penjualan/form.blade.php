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
                    <div class="col-lg-12">
                        <div class="form-group">
                            <h3>Nama: </h3>
                            <label id="nama" for="nama">Nama</label>
                        </div>
                        <div class="form-group">
                            <h3>Tanggal: </h3>
                            <label id="tgl" for="tgl">Tgl Pembelian</label>
                        </div>
                        <div class="form-group">
                            <h3>Total: </h3>
                            <label id="total" for="total">Total Pembelian</label>
                        </div>
                        <form action="" method="post">
                            @csrf
                            @method('post')
                            <div class="form-group">
                                <h3 class="control-label">Status Pembayaran</h3>
                                <div class="">
                                    <select class="form-control" name="statuses" id="statuses">
                                        @foreach ($statuses as $key => $item)
                                            <option value="{{ $key }}">{{ $item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
