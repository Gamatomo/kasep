@extends('layouts.master')

@section('title', 'Laporan Arus Kas')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Laporan SAK ETAP</a></li>
    <li class="active">Arus Kas</li>
@endsection

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Laporan Arus Kas (Cash Flow Statement) - Metode Tidak Langsung</h3>
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
        <h3 class="box-title">Laporan Arus Kas Periode {{ tanggal_indonesia($startDate, false) }} s.d {{ tanggal_indonesia($endDate, false) }}</h3>
    </div>
    <div class="box-body" style="overflow-x: auto;">
        <table class="table table-bordered table-striped" style="font-size: 13px;">
            <tbody>
                <tr style="background-color: #d3d3d3;">
                    <td colspan="2"><strong>AKTIVITAS OPERASIONAL (METODE TIDAK LANGSUNG)</strong></td>
                    <td style="text-align: right;"><strong>Jumlah (Rp.)</strong></td>
                </tr>
                <tr>
                    <td colspan="2">Laba Bersih</td>
                    <td style="text-align: right;">{{ format_uang($report['operating_activities']['net_income']) }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Penyesuaian terhadap:</strong></td>
                    <td style="text-align: right;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 40px;">Perubahan Piutang Usaha</td>
                    <td></td>
                    <td style="text-align: right;">{{ format_uang($report['operating_activities']['change_in_receivables']) }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 40px;">Perubahan Persediaan</td>
                    <td></td>
                    <td style="text-align: right;">{{ format_uang($report['operating_activities']['change_in_inventory']) }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 40px;">Perubahan Utang Usaha</td>
                    <td></td>
                    <td style="text-align: right;">{{ format_uang($report['operating_activities']['change_in_payables']) }}</td>
                </tr>
                <tr style="background-color: #e8f4f8;">
                    <td colspan="2"><strong>Arus Kas Bersih dari Aktivitas Operasional</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['operating_cash_flow']) }}</strong></td>
                </tr>

                <tr style="height: 10px;"></tr>

                <tr style="background-color: #d3d3d3;">
                    <td colspan="2"><strong>AKTIVITAS INVESTASI</strong></td>
                    <td style="text-align: right;"><strong>-</strong></td>
                </tr>
                <tr style="background-color: #e8f4f8;">
                    <td colspan="2"><strong>Arus Kas Bersih dari Aktivitas Investasi</strong></td>
                    <td style="text-align: right;"><strong>-</strong></td>
                </tr>

                <tr style="height: 10px;"></tr>

                <tr style="background-color: #d3d3d3;">
                    <td colspan="2"><strong>AKTIVITAS PENDANAAN</strong></td>
                    <td style="text-align: right;"><strong>-</strong></td>
                </tr>
                <tr style="background-color: #e8f4f8;">
                    <td colspan="2"><strong>Arus Kas Bersih dari Aktivitas Pendanaan</strong></td>
                    <td style="text-align: right;"><strong>-</strong></td>
                </tr>

                <tr style="height: 10px;"></tr>

                <tr>
                    <td colspan="2">Saldo Kas Awal Periode</td>
                    <td style="text-align: right;">{{ format_uang($report['cash_beginning_balance']) }}</td>
                </tr>
                <tr>
                    <td colspan="2">Perubahan Kas Bersih</td>
                    <td style="text-align: right;">{{ format_uang($report['net_change_in_cash']) }}</td>
                </tr>
                <tr style="background-color: #fff3cd;">
                    <td colspan="2"><strong>Saldo Kas Akhir Periode</strong></td>
                    <td style="text-align: right;"><strong>{{ format_uang($report['cash_ending_balance']) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
