<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // Assets
            ['account_code' => '1000', 'name' => 'Assets', 'type' => 'asset', 'parent_id' => null],
            ['account_code' => '1100', 'name' => 'Cash', 'type' => 'asset', 'parent_id' => 1],
            ['account_code' => '1200', 'name' => 'Bank', 'type' => 'asset', 'parent_id' => 1],
            ['account_code' => '1300', 'name' => 'Receivables', 'type' => 'asset', 'parent_id' => 1],

            // Liabilities
            ['account_code' => '2000', 'name' => 'Liabilities', 'type' => 'liability', 'parent_id' => null],
            ['account_code' => '2100', 'name' => 'Accounts Payable', 'type' => 'liability', 'parent_id' => 5],
            ['account_code' => '2200', 'name' => 'Loans', 'type' => 'liability', 'parent_id' => 5],

            // Equity
            ['account_code' => '3000', 'name' => 'Equity', 'type' => 'equity', 'parent_id' => null],
            ['account_code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity', 'parent_id' => 8],

            // Income
            ['account_code' => '4000', 'name' => 'Income', 'type' => 'income', 'parent_id' => null],
            ['account_code' => '4100', 'name' => 'Donations', 'type' => 'income', 'parent_id' => 10],
            ['account_code' => '4200', 'name' => 'Membership Fees', 'type' => 'income', 'parent_id' => 10],

            // Expenses
            ['account_code' => '5000', 'name' => 'Expenses', 'type' => 'expense', 'parent_id' => null],
            ['account_code' => '5100', 'name' => 'Operational Costs', 'type' => 'expense', 'parent_id' => 13],
            ['account_code' => '5200', 'name' => 'Salaries', 'type' => 'expense', 'parent_id' => 13],
            ['account_code' => '5300', 'name' => 'Utilities', 'type' => 'expense', 'parent_id' => 13],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}
