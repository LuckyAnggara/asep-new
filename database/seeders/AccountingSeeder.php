<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Isi tabel account_categories
        $categories = [
            ['code' => '1', 'name' => 'Assets', 'description' => 'Category for all assets'],
            ['code' => '2', 'name' => 'Liabilities', 'description' => 'Category for all liabilities'],
            ['code' => '3', 'name' => 'Equity', 'description' => 'Category for equity accounts'],
            ['code' => '4', 'name' => 'Revenue', 'description' => 'Category for revenue accounts'],
            ['code' => '5', 'name' => 'Expenses', 'description' => 'Category for expense accounts'],
        ];
        DB::table('account_categories')->insert($categories);

        // Isi tabel account_sub_categories
        $subCategories = [
            ['category_id' => 1, 'code' => '1-01', 'name' => 'Fixed Assets', 'description' => 'Sub-category for fixed assets'],
            ['category_id' => 1, 'code' => '1-02', 'name' => 'Current Assets', 'description' => 'Sub-category for current assets'],
            ['category_id' => 2, 'code' => '2-01', 'name' => 'Short-term Liabilities', 'description' => 'Sub-category for short-term liabilities'],
            ['category_id' => 2, 'code' => '2-02', 'name' => 'Long-term Liabilities', 'description' => 'Sub-category for long-term liabilities'],
            ['category_id' => 3, 'code' => '3-01', 'name' => 'Owner Equity', 'description' => 'Sub-category for owner equity'],
            ['category_id' => 4, 'code' => '4-01', 'name' => 'Sales Revenue', 'description' => 'Sub-category for sales revenue'],
            ['category_id' => 5, 'code' => '5-01', 'name' => 'Operating Expenses', 'description' => 'Sub-category for operating expenses'],
            ['category_id' => 5, 'code' => '5-02', 'name' => 'Administrative Expenses', 'description' => 'Sub-category for administrative expenses'],
            ['category_id' => 1, 'code' => '1-03', 'name' => 'Intangible Assets', 'description' => 'Sub-category for intangible assets'],
            ['category_id' => 1, 'code' => '1-04', 'name' => 'Investments', 'description' => 'Sub-category for investments'],
            ['category_id' => 2, 'code' => '2-03', 'name' => 'Accrued Liabilities', 'description' => 'Sub-category for accrued liabilities'],
            ['category_id' => 2, 'code' => '2-04', 'name' => 'Deferred Revenue', 'description' => 'Sub-category for deferred revenue'],
            ['category_id' => 3, 'code' => '3-02', 'name' => 'Retained Earnings', 'description' => 'Sub-category for retained earnings'],
            ['category_id' => 4, 'code' => '4-02', 'name' => 'Service Revenue', 'description' => 'Sub-category for service revenue'],
            ['category_id' => 4, 'code' => '4-03', 'name' => 'Interest Income', 'description' => 'Sub-category for interest income'],
            ['category_id' => 5, 'code' => '5-03', 'name' => 'Marketing Expenses', 'description' => 'Sub-category for marketing expenses'],
            ['category_id' => 5, 'code' => '5-04', 'name' => 'Utilities Expense', 'description' => 'Sub-category for utilities expense'],
        ];
        DB::table('account_sub_categories')->insert($subCategories);

        // Isi tabel chart_of_accounts
        $chartOfAccounts = [
            ['sub_category_id' => 1, 'code' => '1-01-001', 'name' => 'Bank BNI', 'account_number' => '001', 'description' => 'Bank account at BNI'],
            ['sub_category_id' => 1, 'code' => '1-01-002', 'name' => 'Bank Mandiri', 'account_number' => '002', 'description' => 'Bank account at Mandiri'],
            ['sub_category_id' => 2, 'code' => '1-02-001', 'name' => 'Cash on Hand', 'account_number' => '003', 'description' => 'Cash available on hand'],
            ['sub_category_id' => 2, 'code' => '1-02-002', 'name' => 'Accounts Receivable', 'account_number' => '004', 'description' => 'Outstanding customer payments'],
            ['sub_category_id' => 3, 'code' => '2-01-001', 'name' => 'Short-term Loans', 'account_number' => '005', 'description' => 'Short-term financial obligations'],
            ['sub_category_id' => 4, 'code' => '2-02-001', 'name' => 'Long-term Debt', 'account_number' => '006', 'description' => 'Long-term financial obligations'],
            ['sub_category_id' => 5, 'code' => '3-01-001', 'name' => 'Owner Capital', 'account_number' => '007', 'description' => 'Capital contributed by owners'],
            ['sub_category_id' => 6, 'code' => '4-01-001', 'name' => 'Product Sales', 'account_number' => '008', 'description' => 'Revenue from product sales'],
            ['sub_category_id' => 7, 'code' => '5-01-001', 'name' => 'Salaries Expense', 'account_number' => '009', 'description' => 'Expense for employee salaries'],
            ['sub_category_id' => 8, 'code' => '5-02-001', 'name' => 'Office Supplies', 'account_number' => '010', 'description' => 'Expense for office supplies'],
            ['sub_category_id' => 9, 'code' => '1-03-001', 'name' => 'Patents', 'account_number' => '011', 'description' => 'Intangible assets for patents'],
            ['sub_category_id' => 10, 'code' => '1-04-001', 'name' => 'Stock Investments', 'account_number' => '012', 'description' => 'Investments in stocks'],
            ['sub_category_id' => 11, 'code' => '2-03-001', 'name' => 'Accrued Wages', 'account_number' => '013', 'description' => 'Accrued wages payable'],
            ['sub_category_id' => 12, 'code' => '2-04-001', 'name' => 'Deferred Service Revenue', 'account_number' => '014', 'description' => 'Revenue received in advance'],
            ['sub_category_id' => 13, 'code' => '3-02-001', 'name' => 'Retained Earnings - Current Year', 'account_number' => '015', 'description' => 'Retained earnings for the current year'],
            ['sub_category_id' => 14, 'code' => '4-02-001', 'name' => 'Consulting Revenue', 'account_number' => '016', 'description' => 'Revenue from consulting services'],
            ['sub_category_id' => 15, 'code' => '4-03-001', 'name' => 'Interest on Savings', 'account_number' => '017', 'description' => 'Interest income from savings accounts'],
            ['sub_category_id' => 16, 'code' => '5-03-001', 'name' => 'Advertising Expense', 'account_number' => '018', 'description' => 'Expense for advertising'],
            ['sub_category_id' => 17, 'code' => '5-04-001', 'name' => 'Electricity Expense', 'account_number' => '019', 'description' => 'Expense for electricity'],
        ];
        DB::table('chart_of_accounts')->insert($chartOfAccounts);
    }
}
