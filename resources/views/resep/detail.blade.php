@extends('layouts.master')

@section('title')
    Resep (BOM) - {{ $produk->nama_produk }}
@endsection

@section('breadcrumb')
    @parent
    <li><a href="{{ route('resep.index') }}">Daftar Resep</a></li>
    <li class="active">Detail</li>
@endsection

@push('css')
<style>
    @media (max-width: 768px) {
        .dataTables_wrapper .table-resep th,
        .dataTables_wrapper .table-resep td,
        .table-resep th,
        .table-resep td {
            white-space: nowrap !important;
            word-break: normal !important;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Bahan Baku</h3>
            </div>
            <div class="box-body">
                <form action="{{ route('resep.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                    
                    <div class="form-group">
                        <label>Bahan Baku</label>
                        <select name="id_bahan_baku" class="form-control" required>
                            <option value="">-- Pilih Bahan Baku --</option>
                            @foreach ($bahan_baku as $item)
                                <option value="{{ $item->id_bahan_baku }}">{{ $item->nama_bahan_baku }} ({{ $item->satuan }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Kebutuhan</label>
                        <input type="number" name="jumlah" class="form-control" step="any" required value="1">
                        <span class="help-block">Jumlah bahan baku yang dibutuhkan untuk membuat 1 porsi produk ini.</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Tambah ke Resep</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Komponen Resep (BOM)</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-resep">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama Bahan Baku</th>
                        <th>Kebutuhan per Porsi</th>
                        <th>Biaya per Porsi (HPP)</th>
                        <th width="15%"><i class="fa fa-cog"></i> Aksi</th>
                    </thead>
                    <tbody>
                        @php $total_hpp = 0; @endphp
                        @foreach ($resep as $key => $item)
                        @php 
                            $hpp = $item->jumlah * ($item->bahan_baku->harga_satuan ?? 0);
                            $total_hpp += $hpp;
                        @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->bahan_baku->nama_bahan_baku ?? 'Tidak ditemukan' }}</td>
                            <td>{{ $item->jumlah }} {{ $item->bahan_baku->satuan ?? '' }}</td>
                            <td>Rp. {{ format_uang($hpp) }}</td>
                            <td>
                                <form action="{{ route('resep.destroy', $item->id_resep) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komponen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total HPP per Porsi</th>
                            <th>Rp. {{ format_uang($total_hpp) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
