<div class="modal fade" id="modal-options" tabindex="-1" role="dialog" aria-labelledby="modal-options">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-options">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Pilih Opsi Tambahan</h4>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    @csrf
                    <input type="hidden" name="id_produk" id="options_id_produk">
                    <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Pilihan Mie <span class="text-danger">*</span></h4>
                            @foreach ($mie as $m)
                            <div class="radio">
                                <label>
                                    <input type="radio" name="id_mie" value="{{ $m->id_produk }}" required>
                                    {{ $m->nama_produk }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <h4>Jenis Kuah <span class="text-danger">*</span></h4>
                            @foreach ($kuah as $k)
                            <div class="radio">
                                <label>
                                    <input type="radio" name="id_kuah" value="{{ $k->id_produk }}" required>
                                    {{ $k->nama_produk }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Pilihan Level <span class="text-danger">*</span></h4>
                            @foreach ($level as $l)
                            <div class="radio">
                                <label>
                                    <input type="radio" name="id_level" value="{{ $l->id_produk }}" required>
                                    {{ $l->nama_produk }} @if($l->harga_jual > 0) (+Rp. {{ format_uang($l->harga_jual) }}) @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <h4>Topping Tambahan <span class="text-muted">(Opsional)</span></h4>
                            @foreach ($topping as $t)
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="col-xs-8">
                                    <label style="font-weight: normal;">
                                        {{ $t->nama_produk }} (+Rp. {{ format_uang($t->harga_jual) }})
                                    </label>
                                </div>
                                <div class="col-xs-4">
                                    <input type="number" name="toppings[{{ $t->id_produk }}]" class="form-control input-sm" value="0" min="0">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" onclick="submitOptions()"><i class="fa fa-save"></i> Tambah ke Keranjang</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
