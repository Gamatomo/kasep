<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountsTableSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'Assets', 'category' => 'Current Asset', 'normal_balance' => 'debit'],
            ['code' => '1010', 'name' => 'Bank', 'type' => 'Assets', 'category' => 'Current Asset', 'normal_balance' => 'debit'],
            ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'Assets', 'category' => 'Current Asset', 'normal_balance' => 'debit'],
            ['code' => '1400', 'name' => 'Inventory', 'type' => 'Assets', 'category' => 'Current Asset', 'normal_balance' => 'debit'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'Liabilities', 'category' => 'Current Liability', 'normal_balance' => 'credit'],
            ['code' => '3000', 'name' => 'Capital', 'type' => 'Equity', 'category' => 'Owner\'s Equity', 'normal_balance' => 'credit'],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'Equity', 'category' => 'Owner\'s Equity', 'normal_balance' => 'credit'],
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'Revenue', 'category' => 'Revenue', 'normal_balance' => 'credit'],
            ['code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'Expense', 'category' => 'Cost of Sales', 'normal_balance' => 'debit'],
            ['code' => '6000', 'name' => 'Operational Expenses', 'type' => 'Expense', 'category' => 'Operating Expense', 'normal_balance' => 'debit'],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::updateOrCreate(
                ['code' => $account['code']],
                $account
            );
        }
    }
}
