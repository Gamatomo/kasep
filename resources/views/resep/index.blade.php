@extends('layouts.master')

@section('title')
    Daftar Resep (BOM)
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Resep</li>
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
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Pilih Produk untuk Mengatur Resep</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-resep">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th width="15%"><i class="fa fa-cog"></i> Aksi</th>
                    </thead>
                    <tbody>
                        @foreach ($produk as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><span class="label label-success">{{ $item->kode_produk }}</span></td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->kategori->nama_kategori ?? '' }}</td>
                            <td>
                                <a href="{{ route('resep.detail', $item->id_produk) }}" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-list"></i> Atur Resep</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('.table').DataTable({
            autoWidth: false,
            scrollX: true
        });
    });
</script>
@endpush
