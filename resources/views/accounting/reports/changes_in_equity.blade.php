@extends('layouts.master')

@section('title', 'Laporan Perubahan Ekuitas')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Laporan SAK ETAP</a></li>
    <li class="active">Perubahan Ekuitas</li>
@endsection

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Laporan Perubahan Ekuitas (Statement of Changes in Equity)</h3>
    </div>
    <div class="box-body">
        <form method="GET" class="form-inline" style="margin-bottom: 20px;">
            <div class="form-group" style="margin-right: 20px;">
                <label for="start_date">Dari Tanggal:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" style="margin-left: 10px; width: 200px;" value="{{ request('start_date', $startDate->toDateString()) }}">
            </div>
            <div class="form-group" style="margin-right: 20px;">
                <label for="end_date">Sampai Tanggal:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" style="margin-left: 10px; width: 200px;" value="{{ request('end_date', $endDate->toDateString()) }}">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-refresh"></i> Tampilkan
            </button>
            <button type="button" class="btn btn-info" onclick="window.print();" style="margin-left: 10px;">
                <i class="fa fa-print"></i> Cetak
            </button>
        </form>
    </div>
</div>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Laporan Perubahan Ekuitas Periode {{ tanggal_indonesia($startDate, false) }} s.d {{ tanggal_indonesia($endDate, false) }}</h3>
    </div>
    <div class="box-body" style="overflow-x: auto;">
        <table class="table table-bordered table-striped" style="font-size: 13px;">
            <tbody>
                <tr style="background-color: #e8f4f8;">
                    <td><strong>Saldo Ekuitas Awal Periode</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['equity_beginning']) }}</strong></td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;Tambah: Laba Bersih Tahun Ini</td>
                    <td style="text-align: right;">{{ format_uang($report['net_income']) }}</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;Kurang: Pengambilan Pribadi / Dividen</td>
                    <td style="text-align: right;">-</td>
                </tr>
                <tr style="background-color: #fff3cd;">
                    <td><strong>Saldo Ekuitas Akhir Periode</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['equity_ending']) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Perubahan Ekuitas (Peningkatan / Penurunan)</strong></td>
                    <td style="text-align: right; color: {{ $report['changes'] >= 0 ? 'green' : 'red' }};"><strong>{{ format_uang($report['changes']) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
