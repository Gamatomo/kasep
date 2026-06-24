@extends('layouts.master')

@section('title')
    Kehadiran Kasir
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Kehadiran Kasir</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        @if(auth()->user()->level == 1)
                        <th>Kasir</th>
                        @endif
                        <th>Shift</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Penghasilan Sistem</th>
                        <th>Uang Laci (Kasir)</th>
                        <th>Selisih</th>
                        @if(auth()->user()->level == 2)
                        <th>Aksi</th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach($attendances as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                            @if(auth()->user()->level == 1)
                            <td>{{ $item->user->name ?? '-' }}</td>
                            @endif
                            <td>{{ $item->shift }}</td>
                            <td>{{ $item->clock_in }}</td>
                            <td>{{ $item->clock_out ?? '-' }}</td>
                            <td>{{ $item->clock_out ? 'Rp '. format_uang($item->system_revenue) : '-' }}</td>
                            <td>{{ $item->clock_out ? 'Rp '. format_uang($item->earnings) : '-' }}</td>
                            <td>
                                @if($item->clock_out)
                                    @php $selisih = $item->earnings - $item->system_revenue; @endphp
                                    <span class="{{ $selisih < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $selisih < 0 ? '-' : '+' }} Rp {{ format_uang(abs($selisih)) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            @if(auth()->user()->level == 2)
                            <td>
                                @if(!$item->clock_out)
                                <button onclick="clockOutForm('{{ route('attendance.clock_out', $item->id) }}')" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-sign-out"></i> Clock Out</button>
                                @else
                                <span class="label label-success">Selesai</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->level == 2)
<!-- Modal Clock Out -->
<div class="modal fade" id="modal-clock-out" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" class="form-horizontal">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Clock Out</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="earnings" class="col-md-5 control-label">Uang di Laci (Rp)</label>
                        <div class="col-md-6">
                            <input type="number" name="earnings" id="earnings" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    function clockOutForm(url) {
        $('#modal-clock-out').modal('show');
        $('#modal-clock-out form').attr('action', url);
        $('#modal-clock-out [name=earnings]').val('');
    }
</script>
@endpush
