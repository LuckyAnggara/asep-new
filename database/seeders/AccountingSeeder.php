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
        // $categories = [
        //     ['code' => '1', 'name' => 'Assets', 'description' => 'Category for all assets'],
        //     ['code' => '2', 'name' => 'Liabilities', 'description' => 'Category for all liabilities'],
        //     ['code' => '3', 'name' => 'Equity', 'description' => 'Category for equity accounts'],
        //     ['code' => '4', 'name' => 'Revenue', 'description' => 'Category for revenue accounts'],
        //     ['code' => '5', 'name' => 'Expenses', 'description' => 'Category for expense accounts'],
        // ];
        // DB::table('account_categories')->insert($categories);

        // // Isi tabel account_sub_categories
        // $subCategories = [
        //     ['category_id' => 1, 'code' => '1-01', 'name' => 'Fixed Assets', 'description' => 'Sub-category for fixed assets'],
        //     ['category_id' => 1, 'code' => '1-02', 'name' => 'Current Assets', 'description' => 'Sub-category for current assets'],
        //     ['category_id' => 2, 'code' => '2-01', 'name' => 'Short-term Liabilities', 'description' => 'Sub-category for short-term liabilities'],
        //     ['category_id' => 2, 'code' => '2-02', 'name' => 'Long-term Liabilities', 'description' => 'Sub-category for long-term liabilities'],
        //     ['category_id' => 3, 'code' => '3-01', 'name' => 'Owner Equity', 'description' => 'Sub-category for owner equity'],
        //     ['category_id' => 4, 'code' => '4-01', 'name' => 'Sales Revenue', 'description' => 'Sub-category for sales revenue'],
        //     ['category_id' => 5, 'code' => '5-01', 'name' => 'Operating Expenses', 'description' => 'Sub-category for operating expenses'],
        //     ['category_id' => 5, 'code' => '5-02', 'name' => 'Administrative Expenses', 'description' => 'Sub-category for administrative expenses'],
        //     ['category_id' => 1, 'code' => '1-03', 'name' => 'Intangible Assets', 'description' => 'Sub-category for intangible assets'],
        //     ['category_id' => 1, 'code' => '1-04', 'name' => 'Investments', 'description' => 'Sub-category for investments'],
        //     ['category_id' => 2, 'code' => '2-03', 'name' => 'Accrued Liabilities', 'description' => 'Sub-category for accrued liabilities'],
        //     ['category_id' => 2, 'code' => '2-04', 'name' => 'Deferred Revenue', 'description' => 'Sub-category for deferred revenue'],
        //     ['category_id' => 3, 'code' => '3-02', 'name' => 'Retained Earnings', 'description' => 'Sub-category for retained earnings'],
        //     ['category_id' => 4, 'code' => '4-02', 'name' => 'Service Revenue', 'description' => 'Sub-category for service revenue'],
        //     ['category_id' => 4, 'code' => '4-03', 'name' => 'Interest Income', 'description' => 'Sub-category for interest income'],
        //     ['category_id' => 5, 'code' => '5-03', 'name' => 'Marketing Expenses', 'description' => 'Sub-category for marketing expenses'],
        //     ['category_id' => 5, 'code' => '5-04', 'name' => 'Utilities Expense', 'description' => 'Sub-category for utilities expense'],
        // ];
        // DB::table('account_sub_categories')->insert($subCategories);

        // // Isi tabel chart_of_accounts
        // $chartOfAccounts = [
        //     ['sub_category_id' => 1, 'code' => '1-01-001', 'name' => 'Bank BNI', 'account_number' => '001', 'description' => 'Bank account at BNI'],
        //     ['sub_category_id' => 1, 'code' => '1-01-002', 'name' => 'Bank Mandiri', 'account_number' => '002', 'description' => 'Bank account at Mandiri'],
        //     ['sub_category_id' => 2, 'code' => '1-02-001', 'name' => 'Cash on Hand', 'account_number' => '003', 'description' => 'Cash available on hand'],
        //     ['sub_category_id' => 2, 'code' => '1-02-002', 'name' => 'Accounts Receivable', 'account_number' => '004', 'description' => 'Outstanding customer payments'],
        //     ['sub_category_id' => 3, 'code' => '2-01-001', 'name' => 'Short-term Loans', 'account_number' => '005', 'description' => 'Short-term financial obligations'],
        //     ['sub_category_id' => 4, 'code' => '2-02-001', 'name' => 'Long-term Debt', 'account_number' => '006', 'description' => 'Long-term financial obligations'],
        //     ['sub_category_id' => 5, 'code' => '3-01-001', 'name' => 'Owner Capital', 'account_number' => '007', 'description' => 'Capital contributed by owners'],
        //     ['sub_category_id' => 6, 'code' => '4-01-001', 'name' => 'Product Sales', 'account_number' => '008', 'description' => 'Revenue from product sales'],
        //     ['sub_category_id' => 7, 'code' => '5-01-001', 'name' => 'Salaries Expense', 'account_number' => '009', 'description' => 'Expense for employee salaries'],
        //     ['sub_category_id' => 8, 'code' => '5-02-001', 'name' => 'Office Supplies', 'account_number' => '010', 'description' => 'Expense for office supplies'],
        //     ['sub_category_id' => 9, 'code' => '1-03-001', 'name' => 'Patents', 'account_number' => '011', 'description' => 'Intangible assets for patents'],
        //     ['sub_category_id' => 10, 'code' => '1-04-001', 'name' => 'Stock Investments', 'account_number' => '012', 'description' => 'Investments in stocks'],
        //     ['sub_category_id' => 11, 'code' => '2-03-001', 'name' => 'Accrued Wages', 'account_number' => '013', 'description' => 'Accrued wages payable'],
        //     ['sub_category_id' => 12, 'code' => '2-04-001', 'name' => 'Deferred Service Revenue', 'account_number' => '014', 'description' => 'Revenue received in advance'],
        //     ['sub_category_id' => 13, 'code' => '3-02-001', 'name' => 'Retained Earnings - Current Year', 'account_number' => '015', 'description' => 'Retained earnings for the current year'],
        //     ['sub_category_id' => 14, 'code' => '4-02-001', 'name' => 'Consulting Revenue', 'account_number' => '016', 'description' => 'Revenue from consulting services'],
        //     ['sub_category_id' => 15, 'code' => '4-03-001', 'name' => 'Interest on Savings', 'account_number' => '017', 'description' => 'Interest income from savings accounts'],
        //     ['sub_category_id' => 16, 'code' => '5-03-001', 'name' => 'Advertising Expense', 'account_number' => '018', 'description' => 'Expense for advertising'],
        //     ['sub_category_id' => 17, 'code' => '5-04-001', 'name' => 'Electricity Expense', 'account_number' => '019', 'description' => 'Expense for electricity'],
        // ];
        // DB::table('chart_of_accounts')->insert($chartOfAccounts);


        // Isi tabel account_categories
        $categories = [
            ['code' => '1', 'name' => 'Aset', 'description' => 'Kategori untuk semua aset', 'normal' => 'debit'],
            ['code' => '2', 'name' => 'Kewajiban', 'description' => 'Kategori untuk semua kewajiban', 'normal' => 'credit'],
            ['code' => '3', 'name' => 'Ekuitas', 'description' => 'Kategori untuk akun ekuitas', 'normal' => 'credit'],
            ['code' => '4', 'name' => 'Pendapatan', 'description' => 'Kategori untuk akun pendapatan', 'normal' => 'credit'],
            ['code' => '5', 'name' => 'Beban', 'description' => 'Kategori untuk akun beban', 'normal' => 'debit'],
        ];
        DB::table('account_categories')->insert($categories);

        // Isi tabel account_sub_categories
        $subCategories = [
            ['category_id' => 1, 'code' => '1-01', 'name' => 'Aset Tetap', 'description' => 'Sub-kategori untuk aset tetap'],
            ['category_id' => 1, 'code' => '1-02', 'name' => 'Aset Lancar', 'description' => 'Sub-kategori untuk aset lancar'],
            ['category_id' => 2, 'code' => '2-01', 'name' => 'Kewajiban Jangka Pendek', 'description' => 'Sub-kategori untuk kewajiban jangka pendek'],
            ['category_id' => 2, 'code' => '2-02', 'name' => 'Kewajiban Jangka Panjang', 'description' => 'Sub-kategori untuk kewajiban jangka panjang'],
            ['category_id' => 3, 'code' => '3-01', 'name' => 'Ekuitas Pemilik', 'description' => 'Sub-kategori untuk ekuitas pemilik'],
            ['category_id' => 4, 'code' => '4-01', 'name' => 'Pendapatan Penjualan', 'description' => 'Sub-kategori untuk pendapatan penjualan'],
            ['category_id' => 5, 'code' => '5-01', 'name' => 'Beban Operasional', 'description' => 'Sub-kategori untuk beban operasional'],
            ['category_id' => 5, 'code' => '5-02', 'name' => 'Beban Administrasi', 'description' => 'Sub-kategori untuk beban administrasi'],
            ['category_id' => 1, 'code' => '1-03', 'name' => 'Aset Tidak Berwujud', 'description' => 'Sub-kategori untuk aset tidak berwujud'],
            ['category_id' => 1, 'code' => '1-04', 'name' => 'Investasi', 'description' => 'Sub-kategori untuk investasi'],
            ['category_id' => 2, 'code' => '2-03', 'name' => 'Kewajiban Akrual', 'description' => 'Sub-kategori untuk kewajiban akrual'],
            ['category_id' => 2, 'code' => '2-04', 'name' => 'Pendapatan Diterima Dimuka', 'description' => 'Sub-kategori untuk pendapatan diterima di muka'],
            ['category_id' => 3, 'code' => '3-02', 'name' => 'Laba Ditahan', 'description' => 'Sub-kategori untuk laba ditahan'],
            ['category_id' => 4, 'code' => '4-02', 'name' => 'Pendapatan Jasa', 'description' => 'Sub-kategori untuk pendapatan jasa'],
            ['category_id' => 4, 'code' => '4-03', 'name' => 'Pendapatan Bunga', 'description' => 'Sub-kategori untuk pendapatan bunga'],
            ['category_id' => 5, 'code' => '5-03', 'name' => 'Beban Pemasaran', 'description' => 'Sub-kategori untuk beban pemasaran'],
            ['category_id' => 5, 'code' => '5-04', 'name' => 'Beban Utilitas', 'description' => 'Sub-kategori untuk beban utilitas'],
        ];
        DB::table('account_sub_categories')->insert($subCategories);


        $chartOfAccounts = [
            ['sub_category_id' => 1, 'code' => '1-01-001', 'account_number' => 1, 'name' => 'Bank BNI', 'account_number' => '001', 'description' => 'Rekening bank di BNI', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 1, 'code' => '1-01-002', 'account_number' => 1, 'name' => 'Bank Mandiri', 'account_number' => '002', 'description' => 'Rekening bank di Mandiri', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 2, 'code' => '1-02-001', 'account_number' => 1, 'name' => 'Kas di Tangan', 'account_number' => '003', 'description' => 'Kas yang tersedia di tangan', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 2, 'code' => '1-02-002', 'account_number' => 1, 'name' => 'Piutang Usaha', 'account_number' => '004', 'description' => 'Pembayaran pelanggan yang belum diterima', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 3, 'code' => '2-01-001', 'account_number' => 2, 'name' => 'Pinjaman Jangka Pendek', 'account_number' => '005', 'description' => 'Kewajiban keuangan jangka pendek', 'cashflow_type' => 'financing'],
            ['sub_category_id' => 4, 'code' => '2-02-001', 'account_number' => 2, 'name' => 'Hutang Jangka Panjang', 'account_number' => '006', 'description' => 'Kewajiban keuangan jangka panjang', 'cashflow_type' => 'financing'],
            ['sub_category_id' => 5, 'code' => '3-01-001', 'account_number' => 3, 'name' => 'Modal Pemilik', 'account_number' => '007', 'description' => 'Modal yang disumbangkan oleh pemilik', 'cashflow_type' => 'financing'],
            ['sub_category_id' => 6, 'code' => '4-01-001', 'account_number' => 4, 'name' => 'Penjualan Produk', 'account_number' => '008', 'description' => 'Pendapatan dari penjualan produk', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 7, 'code' => '5-01-001', 'account_number' => 5, 'name' => 'Beban Gaji', 'account_number' => '009', 'description' => 'Beban untuk gaji karyawan', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 8, 'code' => '5-02-001', 'account_number' => 5, 'name' => 'Perlengkapan Kantor', 'account_number' => '010', 'description' => 'Beban untuk perlengkapan kantor', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 9, 'code' => '1-03-001', 'account_number' => 1, 'name' => 'Paten', 'account_number' => '011', 'description' => 'Aset tidak berwujud untuk paten', 'cashflow_type' => 'investing'],
            ['sub_category_id' => 10, 'code' => '1-04-001', 'account_number' => 1, 'name' => 'Investasi Saham', 'account_number' => '012', 'description' => 'Investasi dalam saham', 'cashflow_type' => 'investing'],
            ['sub_category_id' => 11, 'code' => '2-03-001', 'account_number' => 2, 'name' => 'Upah Akrual', 'account_number' => '013', 'description' => 'Upah akrual yang harus dibayar', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 12, 'code' => '2-04-001', 'account_number' => 2, 'name' => 'Pendapatan Jasa Diterima Dimuka', 'account_number' => '014', 'description' => 'Pendapatan yang diterima di muka', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 13, 'code' => '3-02-001', 'account_number' => 3, 'name' => 'Laba Ditahan - Tahun Berjalan', 'account_number' => '015', 'description' => 'Laba ditahan untuk tahun berjalan', 'cashflow_type' => null], // Biasanya null atau tidak termasuk dalam cash flow
            ['sub_category_id' => 14, 'code' => '4-02-001', 'account_number' => 4, 'name' => 'Pendapatan Jasa', 'account_number' => '016', 'description' => 'Pendapatan dari jasa yang diberikan', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 15, 'code' => '4-03-001', 'account_number' => 4, 'name' => 'Pendapatan Bunga', 'account_number' => '017', 'description' => 'Pendapatan bunga dari simpanan', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 16, 'code' => '5-03-001', 'account_number' => 5, 'name' => 'Beban Pemasaran', 'account_number' => '018', 'description' => 'Beban untuk pemasaran', 'cashflow_type' => 'operating'],
            ['sub_category_id' => 17, 'code' => '5-04-001', 'account_number' => 5, 'name' => 'Beban Listrik', 'account_number' => '019', 'description' => 'Beban untuk listrik', 'cashflow_type' => 'operating'],
        ];

        DB::table('chart_of_accounts')->insert($chartOfAccounts);
    }
}
