@extends('layouts.master')

@section('title', 'Neraca (Balance Sheet)')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Laporan SAK ETAP</a></li>
    <li class="active">Neraca</li>
@endsection

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Laporan Posisi Keuangan / Neraca</h3>
    </div>
    <div class="box-body">
        <form method="GET" class="form-inline" style="margin-bottom: 20px;">
            <div class="form-group" style="margin-right: 20px;">
                <label for="as_of_date">Tanggal:</label>
                <input type="date" id="as_of_date" name="as_of_date" class="form-control" style="margin-left: 10px; width: 200px;" value="{{ request('as_of_date', $asOfDate->toDateString()) }}">
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
        <h3 class="box-title">Neraca per {{ tanggal_indonesia($asOfDate, false) }}</h3>
    </div>
    <div class="box-body" style="overflow-x: auto;">
        <table class="table table-bordered table-striped" style="font-size: 13px;">
            <tbody>
                <tr style="background-color: #d3d3d3;">
                    <td colspan="2"><strong>AKTIVA (ASSETS)</strong></td>
                    <td style="text-align: right;"><strong>Jumlah (Rp.)</strong></td>
                </tr>
                @forelse($report['assets'] as $asset)
                    <tr>
                        <td style="padding-left: 40px;">{{ $asset['code'] }}</td>
                        <td>{{ $asset['name'] }}</td>
                        <td style="text-align: right;">{{ format_uang($asset['balance']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr style="background-color: #e8f4f8;">
                    <td colspan="2"><strong>TOTAL AKTIVA</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['assets_total']) }}</strong></td>
                </tr>

                <tr style="height: 10px;"></tr>

                <tr style="background-color: #d3d3d3;">
                    <td colspan="2"><strong>PASIVA (LIABILITIES)</strong></td>
                    <td style="text-align: right;"><strong>Jumlah (Rp.)</strong></td>
                </tr>
                @forelse($report['liabilities'] as $liability)
                    <tr>
                        <td style="padding-left: 40px;">{{ $liability['code'] }}</td>
                        <td>{{ $liability['name'] }}</td>
                        <td style="text-align: right;">{{ format_uang($liability['balance']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr style="background-color: #e8f4f8;">
                    <td colspan="2"><strong>TOTAL PASIVA</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['liabilities_total']) }}</strong></td>
                </tr>

                <tr style="height: 10px;"></tr>

                <tr style="background-color: #d3d3d3;">
                    <td colspan="2"><strong>EKUITAS (EQUITY)</strong></td>
                    <td style="text-align: right;"><strong>Jumlah (Rp.)</strong></td>
                </tr>
                @forelse($report['equity'] as $eq)
                    <tr>
                        <td style="padding-left: 40px;">{{ $eq['code'] }}</td>
                        <td>{{ $eq['name'] }}</td>
                        <td style="text-align: right;">{{ format_uang($eq['balance']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr style="background-color: #e8f4f8;">
                    <td colspan="2"><strong>TOTAL EKUITAS</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['equity_total']) }}</strong></td>
                </tr>

                <tr style="background-color: #fff3cd;">
                    <td colspan="2"><strong>TOTAL PASIVA + EKUITAS</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['liabilities_total'] + $report['equity_total']) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
