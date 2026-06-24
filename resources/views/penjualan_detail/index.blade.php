@extends('layouts.master')

@section('title')
    Transaksi Penjualan
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

    .table-penjualan tbody tr:last-child {
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
    <li class="active">Transaksi Penjaualn</li>
@endsection

@section('content')
<div class="row">
    <!-- Left Column: Menu Grid -->
    <div class="col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-cutlery"></i> Pilih Menu</h3>
            </div>
            <div class="box-body" style="height: 75vh; overflow-y: auto;">
                <div class="row">
                    @foreach($produk as $p)
                    <div class="col-md-6 col-sm-6 col-xs-12 mb-3" style="margin-bottom: 15px;">
                        <div class="card" style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; text-align: center; cursor: pointer; transition: 0.3s; background: #fff;" 
                             onclick="pilihProduk('{{ $p->id_produk }}')" 
                             onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)'" 
                             onmouseout="this.style.boxShadow='none'">
                            <div style="font-size: 3em; color: #f39c12;"><i class="fa fa-shopping-basket"></i></div>
                            <h4 style="font-size: 1.1em; font-weight: bold; margin-top: 10px; min-height: 40px;">{{ $p->nama_produk }}</h4>
                            <p class="text-success" style="font-weight: bold;">Rp. {{ format_uang($p->harga_jual) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Cart & Payment -->
    <div class="col-lg-6">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Keranjang</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jml</th>
                        <th>Subtotal</th>
                        <th width="10%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="tampil-bayar bg-primary" style="margin-top: 15px; font-size: 3em; height: 80px; padding-top: 10px;"></div>
                <div class="tampil-terbilang" style="margin-bottom: 15px;"></div>

                <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                    @csrf
                    <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                    <input type="hidden" name="total" id="total">
                    <input type="hidden" name="total_item" id="total_item">
                    <input type="hidden" name="bayar" id="bayar">
                    <input type="hidden" name="diskon" id="diskon" value="0">

                    <div class="form-group row">
                        <label for="totalrp" class="col-lg-3 control-label">Total</label>
                        <div class="col-lg-9">
                            <input type="text" id="totalrp" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bayar" class="col-lg-3 control-label">Bayar</label>
                        <div class="col-lg-9">
                            <input type="text" id="bayarrp" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="diterima" class="col-lg-3 control-label">Diterima</label>
                        <div class="col-lg-9">
                            <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $penjualan->diterima ?? 0 }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kembali" class="col-lg-3 control-label">Kembali</label>
                        <div class="col-lg-9">
                            <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="metode" class="col-lg-3 control-label">Metode</label>
                        <div class="col-lg-9">
                            <select name="metode" id="metode" class="form-control" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="Cash">Cash</option>
                                <option value="Qris">Qris</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-block btn-lg btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan_detail.options')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-penjualan').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksi.data', $id_penjualan) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
            setTimeout(() => {
                $('#diterima').trigger('input');
            }, 300);
        });
        table2 = $('.table-produk').DataTable();

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/transaksi') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });

        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($('#diskon').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        $('.btn-simpan').on('click', function () {
            $('.form-penjualan').submit();
        });
    });

    function pilihProduk(id) {
        $('#options_id_produk').val(id);
        $('#modal-options').modal('show');
        $('#form-options')[0].reset();
    }

    function submitOptions() {
        if (!$('#form-options')[0].checkValidity()) {
            $('#form-options')[0].reportValidity();
            return;
        }

        $.post('{{ route('transaksi.store') }}', $('#form-options').serialize())
            .done(response => {
                $('#modal-options').modal('hide');
                table.ajax.reload(() => loadForm($('#diskon').val()));
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
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function loadForm(diskon = 0, diterima = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Bayar: Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);

                $('#kembali').val('Rp.'+ response.kembalirp);
                if ($('#diterima').val() != 0) {
                    $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                    $('.tampil-terbilang').text(response.kembali_terbilang);
                }
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
</script>
@endpush