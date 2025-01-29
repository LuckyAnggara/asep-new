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
        ];
        DB::table('chart_of_accounts')->insert($chartOfAccounts);
    }
}
