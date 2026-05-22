@extends('layouts.master')

@section('title', 'Laporan Laba Rugi')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Laporan SAK ETAP</a></li>
    <li class="active">Laba Rugi</li>
@endsection

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Laporan Laba Rugi (Income Statement)</h3>
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
        <h3 class="box-title">Laporan Laba Rugi Periode {{ tanggal_indonesia($startDate, false) }} s.d {{ tanggal_indonesia($endDate, false) }}</h3>
    </div>
    <div class="box-body" style="overflow-x: auto;">
        <table class="table table-bordered table-striped" style="font-size: 13px;">
            <tbody>
                <tr style="background-color: #d3d3d3;">
                    <td colspan="2"><strong>PENDAPATAN (REVENUE)</strong></td>
                    <td style="text-align: right;"><strong>Jumlah (Rp.)</strong></td>
                </tr>
                @forelse($report['revenue'] as $rev)
                    <tr>
                        <td style="padding-left: 40px;">{{ $rev['code'] }}</td>
                        <td>{{ $rev['name'] }}</td>
                        <td style="text-align: right;">{{ format_uang($rev['balance']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr style="background-color: #e8f4f8;">
                    <td colspan="2"><strong>TOTAL PENDAPATAN</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['total_revenue']) }}</strong></td>
                </tr>

                <tr style="height: 10px;"></tr>

                <tr style="background-color: #d3d3d3;">
                    <td colspan="2"><strong>BEBAN / BIAYA (EXPENSES)</strong></td>
                    <td style="text-align: right;"><strong>Jumlah (Rp.)</strong></td>
                </tr>
                @forelse($report['expenses'] as $exp)
                    <tr>
                        <td style="padding-left: 40px;">{{ $exp['code'] }}</td>
                        <td>{{ $exp['name'] }}</td>
                        <td style="text-align: right;">{{ format_uang($exp['balance']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr style="background-color: #e8f4f8;">
                    <td colspan="2"><strong>TOTAL BEBAN / BIAYA</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['total_expenses']) }}</strong></td>
                </tr>

                <tr style="height: 10px;"></tr>

                <tr style="background-color: #fff3cd;">
                    <td colspan="2"><strong>LABA BERSIH (NET INCOME)</strong></td>
                    <td style="text-align: right; color: {{ $report['net_income'] >= 0 ? 'green' : 'red' }};"><strong>{{ format_uang($report['net_income']) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
