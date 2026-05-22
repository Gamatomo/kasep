<?php

namespace App\Http\Controllers;

use App\Services\Accounting\FinancialReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    protected $reportService;

    public function __construct(FinancialReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('accounting.reports.index');
    }

    public function balanceSheet(Request $request)
    {
        $request->validate([
            'as_of_date' => 'required|date',
            'branch_id' => 'nullable|integer|exists:cabang,id',
        ]);

        $asOfDate = Carbon::createFromFormat('Y-m-d', $request->as_of_date);
        $branchId = $request->branch_id;

        $report = $this->reportService->getBalanceSheet($asOfDate, $branchId);

        return response()->json($report);
    }

    public function incomeStatement(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'branch_id' => 'nullable|integer|exists:cabang,id',
        ]);

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
        $branchId = $request->branch_id;

        $report = $this->reportService->getIncomeStatement($startDate, $endDate, $branchId);

        return response()->json($report);
    }

    public function statementOfChangesInEquity(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'branch_id' => 'nullable|integer|exists:cabang,id',
        ]);

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
        $branchId = $request->branch_id;

        $report = $this->reportService->getStatementOfChangesInEquity($startDate, $endDate, $branchId);

        return response()->json($report);
    }

    public function cashFlowStatement(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'branch_id' => 'nullable|integer|exists:cabang,id',
        ]);

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
        $branchId = $request->branch_id;

        $report = $this->reportService->getCashFlowStatement($startDate, $endDate, $branchId);

        return response()->json($report);
    }

    public function showBalanceSheet(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::createFromFormat('Y-m-d', $request->as_of_date) : Carbon::today();
        $branchId = $request->branch_id;

        $report = $this->reportService->getBalanceSheet($asOfDate, $branchId);

        return view('accounting.reports.balance_sheet', compact('report', 'asOfDate', 'branchId'));
    }

    public function showIncomeStatement(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date) : Carbon::now()->startOfYear();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date) : Carbon::today();
        $branchId = $request->branch_id;

        $report = $this->reportService->getIncomeStatement($startDate, $endDate, $branchId);

        return view('accounting.reports.income_statement', compact('report', 'startDate', 'endDate', 'branchId'));
    }

    public function showChangesInEquity(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date) : Carbon::now()->startOfYear();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date) : Carbon::today();
        $branchId = $request->branch_id;

        $report = $this->reportService->getStatementOfChangesInEquity($startDate, $endDate, $branchId);

        return view('accounting.reports.changes_in_equity', compact('report', 'startDate', 'endDate', 'branchId'));
    }

    public function showCashFlow(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date) : Carbon::now()->startOfYear();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date) : Carbon::today();
        $branchId = $request->branch_id;

        $report = $this->reportService->getCashFlowStatement($startDate, $endDate, $branchId);

        return view('accounting.reports.cash_flow', compact('report', 'startDate', 'endDate', 'branchId'));
    }
}
