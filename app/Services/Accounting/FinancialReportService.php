<?php

namespace App\Services\Accounting;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FinancialReportService
{
    public function getBalanceSheet(Carbon $asOfDate, ?int $branchId = null): array
    {
        $balances = $this->getAccountBalances($asOfDate, $branchId);

        $assets = $this->filterByType($balances, 'Assets');
        $liabilities = $this->filterByType($balances, 'Liabilities');
        $equity = $this->filterByType($balances, 'Equity');

        return [
            'as_of_date' => $asOfDate->toDateString(),
            'assets' => $assets,
            'assets_total' => $assets->sum('balance'),
            'liabilities' => $liabilities,
            'liabilities_total' => $liabilities->sum('balance'),
            'equity' => $equity,
            'equity_total' => $equity->sum('balance'),
        ];
    }

    public function getIncomeStatement(Carbon $startDate, Carbon $endDate, ?int $branchId = null): array
    {
        $balances = $this->getAccountBalances($endDate, $branchId, $startDate);

        $revenue = $this->filterByType($balances, 'Revenue');
        $expenses = $this->filterByType($balances, 'Expense');

        $totalRevenue = $revenue->sum('balance');
        $totalExpenses = $expenses->sum('balance');
        $netIncome = $totalRevenue - $totalExpenses;

        return [
            'period_start' => $startDate->toDateString(),
            'period_end' => $endDate->toDateString(),
            'revenue' => $revenue,
            'total_revenue' => $totalRevenue,
            'expenses' => $expenses,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
        ];
    }

    public function getStatementOfChangesInEquity(Carbon $startDate, Carbon $endDate, ?int $branchId = null): array
    {
        $previousYearEnd = $startDate->copy()->subDay()->startOfYear();

        $equityStart = $this->getAccountBalances($previousYearEnd, $branchId);
        $equityStart = $this->filterByType($equityStart, 'Equity')->sum('balance');

        $incomeStatement = $this->getIncomeStatement($startDate, $endDate, $branchId);
        $netIncome = $incomeStatement['net_income'];

        $equityEnd = $this->getAccountBalances($endDate, $branchId);
        $equityEnd = $this->filterByType($equityEnd, 'Equity')->sum('balance');

        return [
            'period_start' => $startDate->toDateString(),
            'period_end' => $endDate->toDateString(),
            'equity_beginning' => $equityStart,
            'net_income' => $netIncome,
            'equity_ending' => $equityEnd,
            'changes' => $equityEnd - $equityStart,
        ];
    }

    public function getCashFlowStatement(Carbon $startDate, Carbon $endDate, ?int $branchId = null): array
    {
        $incomeStatement = $this->getIncomeStatement($startDate, $endDate, $branchId);
        $netIncome = $incomeStatement['net_income'];

        $balanceStartOfPeriod = $this->getAccountBalances($startDate->copy()->subDay(), $branchId);
        $balanceEndOfPeriod = $this->getAccountBalances($endDate, $branchId);

        $receivablesStart = $this->filterByCode($balanceStartOfPeriod, '1200')->sum('balance');
        $receivablesEnd = $this->filterByCode($balanceEndOfPeriod, '1200')->sum('balance');
        $changeInReceivables = $receivablesStart - $receivablesEnd;

        $inventoryStart = $this->filterByCode($balanceStartOfPeriod, '1400')->sum('balance');
        $inventoryEnd = $this->filterByCode($balanceEndOfPeriod, '1400')->sum('balance');
        $changeInInventory = $inventoryStart - $inventoryEnd;

        $payablesStart = $this->filterByCode($balanceStartOfPeriod, '2000')->sum('balance');
        $payablesEnd = $this->filterByCode($balanceEndOfPeriod, '2000')->sum('balance');
        $changeInPayables = $payablesEnd - $payablesStart;

        $operatingCashFlow = $netIncome + $changeInInventory + $changeInReceivables + $changeInPayables;

        $cashStart = $this->filterByCode($balanceStartOfPeriod, ['1000', '1010'])->sum('balance');
        $cashEnd = $this->filterByCode($balanceEndOfPeriod, ['1000', '1010'])->sum('balance');
        $netChangeInCash = $cashEnd - $cashStart;

        return [
            'period_start' => $startDate->toDateString(),
            'period_end' => $endDate->toDateString(),
            'net_income' => $netIncome,
            'operating_activities' => [
                'net_income' => $netIncome,
                'change_in_receivables' => $changeInReceivables,
                'change_in_inventory' => $changeInInventory,
                'change_in_payables' => $changeInPayables,
            ],
            'operating_cash_flow' => $operatingCashFlow,
            'cash_beginning_balance' => $cashStart,
            'cash_ending_balance' => $cashEnd,
            'net_change_in_cash' => $netChangeInCash,
        ];
    }

    protected function getAccountBalances(Carbon $asOfDate, ?int $branchId = null, ?Carbon $periodStart = null): Collection
    {
        $accounts = ChartOfAccount::where('is_active', true)->get();

        return $accounts->map(function ($account) use ($asOfDate, $branchId, $periodStart) {
            $query = JournalEntry::with('items')
                ->whereDate('date', '<=', $asOfDate);

            if ($periodStart) {
                $query->whereDate('date', '>=', $periodStart);
            }

            if ($branchId) {
                $query->where('id_cabang', $branchId);
            }

            $entries = $query->get();

            $debitTotal = 0;
            $creditTotal = 0;

            foreach ($entries as $entry) {
                foreach ($entry->items as $item) {
                    if ($item->account_id === $account->id) {
                        $debitTotal += $item->debit;
                        $creditTotal += $item->credit;
                    }
                }
            }

            $balance = 0;
            if ($account->normal_balance === 'debit') {
                $balance = $debitTotal - $creditTotal;
            } else {
                $balance = $creditTotal - $debitTotal;
            }

            return [
                'code' => $account->code,
                'name' => $account->name,
                'type' => $account->type,
                'category' => $account->category,
                'normal_balance' => $account->normal_balance,
                'debit' => $debitTotal,
                'credit' => $creditTotal,
                'balance' => $balance,
            ];
        })->filter(function ($item) {
            return $item['balance'] !== 0;
        });
    }

    protected function filterByType(Collection $accounts, string $type): Collection
    {
        return $accounts->filter(function ($item) use ($type) {
            return $item['type'] === $type;
        })->values();
    }

    protected function filterByCode(Collection $accounts, $codes): Collection
    {
        $codes = is_array($codes) ? $codes : [$codes];
        return $accounts->filter(function ($item) use ($codes) {
            return in_array($item['code'], $codes);
        })->values();
    }
}
