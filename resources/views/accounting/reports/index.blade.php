@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>SAK ETAP Financial Reports</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Financial Reports</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Report Generator</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="report_type">Select Report Type:</label>
                            <select id="report_type" class="form-control" style="width: 300px;">
                                <option value="">-- Choose a Report --</option>
                                <option value="balance_sheet">Balance Sheet (Laporan Posisi Keuangan)</option>
                                <option value="income_statement">Income Statement (Laporan Laba Rugi)</option>
                                <option value="statement_of_changes">Statement of Changes in Equity (Laporan Perubahan Ekuitas)</option>
                                <option value="cash_flow">Cash Flow Statement (Laporan Arus Kas)</option>
                            </select>
                        </div>

                        <div id="balance_sheet_params" style="display: none;" class="report-params">
                            <div class="form-group">
                                <label for="bs_as_of_date">As of Date:</label>
                                <input type="date" id="bs_as_of_date" class="form-control" style="width: 300px;" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div id="income_statement_params" style="display: none;" class="report-params">
                            <div class="form-group">
                                <label for="is_start_date">Start Date:</label>
                                <input type="date" id="is_start_date" class="form-control" style="width: 300px;" value="{{ date('Y-01-01') }}">
                            </div>
                            <div class="form-group">
                                <label for="is_end_date">End Date:</label>
                                <input type="date" id="is_end_date" class="form-control" style="width: 300px;" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div id="statement_of_changes_params" style="display: none;" class="report-params">
                            <div class="form-group">
                                <label for="sc_start_date">Start Date:</label>
                                <input type="date" id="sc_start_date" class="form-control" style="width: 300px;" value="{{ date('Y-01-01') }}">
                            </div>
                            <div class="form-group">
                                <label for="sc_end_date">End Date:</label>
                                <input type="date" id="sc_end_date" class="form-control" style="width: 300px;" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div id="cash_flow_params" style="display: none;" class="report-params">
                            <div class="form-group">
                                <label for="cf_start_date">Start Date:</label>
                                <input type="date" id="cf_start_date" class="form-control" style="width: 300px;" value="{{ date('Y-01-01') }}">
                            </div>
                            <div class="form-group">
                                <label for="cf_end_date">End Date:</label>
                                <input type="date" id="cf_end_date" class="form-control" style="width: 300px;" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <button id="generate_report" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="fa fa-file"></i> Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="report_results" style="display: none; margin-top: 20px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="report_title">Report Results</h3>
                        </div>
                        <div class="box-body" id="report_content" style="overflow-x: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.getElementById('report_type').addEventListener('change', function() {
    document.querySelectorAll('.report-params').forEach(el => el.style.display = 'none');
    const selected = this.value;
    if (selected) {
        document.getElementById(selected + '_params').style.display = 'block';
    }
});

document.getElementById('generate_report').addEventListener('click', function() {
    const reportType = document.getElementById('report_type').value;
    if (!reportType) {
        alert('Please select a report type');
        return;
    }

    let url = '';
    if (reportType === 'balance_sheet') {
        const date = document.getElementById('bs_as_of_date').value;
        url = `/api/accounting/balance-sheet?as_of_date=${date}`;
    } else if (reportType === 'income_statement') {
        const start = document.getElementById('is_start_date').value;
        const end = document.getElementById('is_end_date').value;
        url = `/api/accounting/income-statement?start_date=${start}&end_date=${end}`;
    } else if (reportType === 'statement_of_changes') {
        const start = document.getElementById('sc_start_date').value;
        const end = document.getElementById('sc_end_date').value;
        url = `/api/accounting/statement-of-changes?start_date=${start}&end_date=${end}`;
    } else if (reportType === 'cash_flow') {
        const start = document.getElementById('cf_start_date').value;
        const end = document.getElementById('cf_end_date').value;
        url = `/api/accounting/cash-flow?start_date=${start}&end_date=${end}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            displayReport(reportType, data);
            document.getElementById('report_results').style.display = 'block';
        })
        .catch(error => {
            alert('Error generating report: ' + error);
            console.error(error);
        });
});

function displayReport(type, data) {
    let html = '';

    if (type === 'balance_sheet') {
        html = `
            <h4>Balance Sheet as of ${data.as_of_date}</h4>
            <table class="table table-bordered">
                <tr><th colspan="2"><strong>ASSETS</strong></th><th style="text-align: right;"><strong>Amount</strong></th></tr>
                ${data.assets.map(a => `<tr><td>${a.code}</td><td>${a.name}</td><td style="text-align: right;">Rp. ${formatNumber(a.balance)}</td></tr>`).join('')}
                <tr><td colspan="2"><strong>Total Assets</strong></td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.assets_total)}</strong></td></tr>
                <tr><th colspan="2"><strong>LIABILITIES</strong></th><th style="text-align: right;"><strong>Amount</strong></th></tr>
                ${data.liabilities.map(l => `<tr><td>${l.code}</td><td>${l.name}</td><td style="text-align: right;">Rp. ${formatNumber(l.balance)}</td></tr>`).join('')}
                <tr><td colspan="2"><strong>Total Liabilities</strong></td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.liabilities_total)}</strong></td></tr>
                <tr><th colspan="2"><strong>EQUITY</strong></th><th style="text-align: right;"><strong>Amount</strong></th></tr>
                ${data.equity.map(e => `<tr><td>${e.code}</td><td>${e.name}</td><td style="text-align: right;">Rp. ${formatNumber(e.balance)}</td></tr>`).join('')}
                <tr><td colspan="2"><strong>Total Equity</strong></td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.equity_total)}</strong></td></tr>
                <tr><td colspan="2"><strong>Total Liabilities + Equity</strong></td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.liabilities_total + data.equity_total)}</strong></td></tr>
            </table>
        `;
    } else if (type === 'income_statement') {
        html = `
            <h4>Income Statement (${data.period_start} to ${data.period_end})</h4>
            <table class="table table-bordered">
                <tr><th colspan="2"><strong>REVENUE</strong></th><th style="text-align: right;"><strong>Amount</strong></th></tr>
                ${data.revenue.map(r => `<tr><td>${r.code}</td><td>${r.name}</td><td style="text-align: right;">Rp. ${formatNumber(r.balance)}</td></tr>`).join('')}
                <tr><td colspan="2"><strong>Total Revenue</strong></td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.total_revenue)}</strong></td></tr>
                <tr><th colspan="2"><strong>EXPENSES</strong></th><th style="text-align: right;"><strong>Amount</strong></th></tr>
                ${data.expenses.map(e => `<tr><td>${e.code}</td><td>${e.name}</td><td style="text-align: right;">Rp. ${formatNumber(e.balance)}</td></tr>`).join('')}
                <tr><td colspan="2"><strong>Total Expenses</strong></td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.total_expenses)}</strong></td></tr>
                <tr><td colspan="2"><strong>Net Income</strong></td><td style="text-align: right;"><strong style="color: ${data.net_income >= 0 ? 'green' : 'red'};">Rp. ${formatNumber(data.net_income)}</strong></td></tr>
            </table>
        `;
    } else if (type === 'statement_of_changes') {
        html = `
            <h4>Statement of Changes in Equity (${data.period_start} to ${data.period_end})</h4>
            <table class="table table-bordered">
                <tr><td>Equity at Beginning of Period</td><td style="text-align: right;">Rp. ${formatNumber(data.equity_beginning)}</td></tr>
                <tr><td>Add: Net Income</td><td style="text-align: right;">Rp. ${formatNumber(data.net_income)}</td></tr>
                <tr><td>Equity at End of Period</td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.equity_ending)}</strong></td></tr>
                <tr><td>Total Changes in Equity</td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.changes)}</strong></td></tr>
            </table>
        `;
    } else if (type === 'cash_flow') {
        html = `
            <h4>Cash Flow Statement (${data.period_start} to ${data.period_end})</h4>
            <table class="table table-bordered">
                <tr><th colspan="2"><strong>OPERATING ACTIVITIES (Indirect Method)</strong></th><th style="text-align: right;"><strong>Amount</strong></th></tr>
                <tr><td colspan="2">Net Income</td><td style="text-align: right;">Rp. ${formatNumber(data.operating_activities.net_income)}</td></tr>
                <tr><td colspan="2">Adjustments:</td><td style="text-align: right;"></td></tr>
                <tr><td>&nbsp;&nbsp;&nbsp;Change in Accounts Receivable</td><td></td><td style="text-align: right;">Rp. ${formatNumber(data.operating_activities.change_in_receivables)}</td></tr>
                <tr><td>&nbsp;&nbsp;&nbsp;Change in Inventory</td><td></td><td style="text-align: right;">Rp. ${formatNumber(data.operating_activities.change_in_inventory)}</td></tr>
                <tr><td>&nbsp;&nbsp;&nbsp;Change in Accounts Payable</td><td></td><td style="text-align: right;">Rp. ${formatNumber(data.operating_activities.change_in_payables)}</td></tr>
                <tr><td colspan="2"><strong>Net Cash from Operating Activities</strong></td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.operating_cash_flow)}</strong></td></tr>
                <tr><td colspan="2">Cash at Beginning of Period</td><td style="text-align: right;">Rp. ${formatNumber(data.cash_beginning_balance)}</td></tr>
                <tr><td colspan="2"><strong>Cash at End of Period</strong></td><td style="text-align: right;"><strong>Rp. ${formatNumber(data.cash_ending_balance)}</strong></td></tr>
                <tr><td colspan="2">Net Change in Cash</td><td style="text-align: right;">Rp. ${formatNumber(data.net_change_in_cash)}</td></tr>
            </table>
        `;
    }

    document.getElementById('report_content').innerHTML = html;
}

function formatNumber(num) {
    return Math.abs(num).toLocaleString('id-ID');
}
</script>
@endsection
