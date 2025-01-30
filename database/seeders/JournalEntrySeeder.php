<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JournalEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entries = [
            [
                'date' => Carbon::now()->subDays(10),
                'description' => 'Purchase of Office Supplies',
                'details' => [
                    ['chart_of_accounts_id' => 10, 'debit' => 500, 'credit' => 0], // Supplies
                    ['chart_of_accounts_id' => 17, 'debit' => 0, 'credit' => 500], // Accounts Payable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(9),
                'description' => 'Payment to Supplier',
                'details' => [
                    ['chart_of_accounts_id' => 17, 'debit' => 500, 'credit' => 0], // Accounts Payable
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 500], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(8),
                'description' => 'Client Payment for Services',
                'details' => [
                    ['chart_of_accounts_id' => 3, 'debit' => 2000, 'credit' => 0], // Cash
                    ['chart_of_accounts_id' => 4, 'debit' => 0, 'credit' => 2000], // Accounts Receivable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(7),
                'description' => 'Rent Payment',
                'details' => [
                    ['chart_of_accounts_id' => 19, 'debit' => 1000, 'credit' => 0], // Rent Expense
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 1000], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(6),
                'description' => 'Salary Payment',
                'details' => [
                    ['chart_of_accounts_id' => 9, 'debit' => 3000, 'credit' => 0], // Salaries Expense
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 3000], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(5),
                'description' => 'Purchase of Equipment',
                'details' => [
                    ['chart_of_accounts_id' => 18, 'debit' => 5000, 'credit' => 0], // Equipment
                    ['chart_of_accounts_id' => 2, 'debit' => 0, 'credit' => 5000], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(4),
                'description' => 'Owner Investment',
                'details' => [
                    ['chart_of_accounts_id' => 3, 'debit' => 7000, 'credit' => 0], // Cash
                    ['chart_of_accounts_id' => 7, 'debit' => 0, 'credit' => 7000], // Owner’s Capital
                ],
            ],
            [
                'date' => Carbon::now()->subDays(3),
                'description' => 'Service Revenue Earned',
                'details' => [
                    ['chart_of_accounts_id' => 4, 'debit' => 5000, 'credit' => 0], // Accounts Receivable
                    ['chart_of_accounts_id' => 8, 'debit' => 0, 'credit' => 5000], // Service Revenue
                ],
            ],
            [
                'date' => Carbon::now()->subDays(2),
                'description' => 'Partial Payment Received from Client',
                'details' => [
                    ['chart_of_accounts_id' => 3, 'debit' => 2500, 'credit' => 0], // Cash
                    ['chart_of_accounts_id' => 4, 'debit' => 0, 'credit' => 2500], // Accounts Receivable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(40),
                'description' => 'Purchase of Office Furniture',
                'details' => [
                    ['chart_of_accounts_id' => 1, 'debit' => 1200, 'credit' => 0], // Fixed Assets (Bank BNI)
                    ['chart_of_accounts_id' => 17, 'debit' => 0, 'credit' => 1200], // Accounts Payable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(39),
                'description' => 'Payment for Office Furniture',
                'details' => [
                    ['chart_of_accounts_id' => 17, 'debit' => 1200, 'credit' => 0], // Accounts Payable
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 1200], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(38),
                'description' => 'Revenue from Consulting Services',
                'details' => [
                    ['chart_of_accounts_id' => 3, 'debit' => 1500, 'credit' => 0], // Cash on Hand
                    ['chart_of_accounts_id' => 14, 'debit' => 0, 'credit' => 1500], // Consulting Revenue
                ],
            ],
            [
                'date' => Carbon::now()->subDays(37),
                'description' => 'Payment of Marketing Expenses',
                'details' => [
                    ['chart_of_accounts_id' => 16, 'debit' => 600, 'credit' => 0], // Marketing Expenses
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 600], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(36),
                'description' => 'Purchase of Computer Equipment',
                'details' => [
                    ['chart_of_accounts_id' => 1, 'debit' => 2500, 'credit' => 0], // Fixed Assets (Bank BNI)
                    ['chart_of_accounts_id' => 17, 'debit' => 0, 'credit' => 2500], // Accounts Payable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(35),
                'description' => 'Payment for Computer Equipment',
                'details' => [
                    ['chart_of_accounts_id' => 17, 'debit' => 2500, 'credit' => 0], // Accounts Payable
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 2500], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(34),
                'description' => 'Interest Income from Investments',
                'details' => [
                    ['chart_of_accounts_id' => 3, 'debit' => 200, 'credit' => 0], // Cash on Hand
                    ['chart_of_accounts_id' => 15, 'debit' => 0, 'credit' => 200], // Interest Income
                ],
            ],
            [
                'date' => Carbon::now()->subDays(33),
                'description' => 'Payment of Office Rent',
                'details' => [
                    ['chart_of_accounts_id' => 5, 'debit' => 800, 'credit' => 0], // Rent Expense
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 800], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(32),
                'description' => 'Revenue from Product Sales',
                'details' => [
                    ['chart_of_accounts_id' => 3, 'debit' => 3000, 'credit' => 0], // Cash on Hand
                    ['chart_of_accounts_id' => 8, 'debit' => 0, 'credit' => 3000], // Product Sales
                ],
            ],
            [
                'date' => Carbon::now()->subDays(31),
                'description' => 'Payment of Administrative Expenses',
                'details' => [
                    ['chart_of_accounts_id' => 18, 'debit' => 400, 'credit' => 0], // Administrative Expenses
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 400], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(30),
                'description' => 'Purchase of Office Supplies',
                'details' => [
                    ['chart_of_accounts_id' => 10, 'debit' => 300, 'credit' => 0], // Office Supplies
                    ['chart_of_accounts_id' => 17, 'debit' => 0, 'credit' => 300], // Accounts Payable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(29),
                'description' => 'Payment to Supplier for Office Supplies',
                'details' => [
                    ['chart_of_accounts_id' => 17, 'debit' => 300, 'credit' => 0], // Accounts Payable
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 300], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(28),
                'description' => 'Depreciation of Office Equipment',
                'details' => [
                    ['chart_of_accounts_id' => 1, 'debit' => 0, 'credit' => 150], // Fixed Assets (Depreciation)
                    ['chart_of_accounts_id' => 9, 'debit' => 150, 'credit' => 0], // Depreciation Expense
                ],
            ],
            [
                'date' => Carbon::now()->subDays(27),
                'description' => 'Payment of Utilities',
                'details' => [
                    ['chart_of_accounts_id' => 17, 'debit' => 250, 'credit' => 0], // Utilities Expense
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 250], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(26),
                'description' => 'Revenue from Service Fees',
                'details' => [
                    ['chart_of_accounts_id' => 3, 'debit' => 1200, 'credit' => 0], // Cash on Hand
                    ['chart_of_accounts_id' => 14, 'debit' => 0, 'credit' => 1200], // Consulting Revenue
                ],
            ],
            [
                'date' => Carbon::now()->subDays(25),
                'description' => 'Payment of Advertising Expenses',
                'details' => [
                    ['chart_of_accounts_id' => 16, 'debit' => 500, 'credit' => 0], // Advertising Expense
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 500], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(24),
                'description' => 'Purchase of Inventory',
                'details' => [
                    ['chart_of_accounts_id' => 4, 'debit' => 1000, 'credit' => 0], // Accounts Receivable
                    ['chart_of_accounts_id' => 17, 'debit' => 0, 'credit' => 1000], // Accounts Payable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(23),
                'description' => 'Payment for Inventory',
                'details' => [
                    ['chart_of_accounts_id' => 17, 'debit' => 1000, 'credit' => 0], // Accounts Payable
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 1000], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(22),
                'description' => 'Dividend Paid to Shareholders',
                'details' => [
                    ['chart_of_accounts_id' => 7, 'debit' => 2000, 'credit' => 0], // Owner Capital
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 2000], // Cash on Hand
                ],
            ],
            [
                'date' => Carbon::now()->subDays(21),
                'description' => 'Payment of Loan Principal',
                'details' => [
                    ['chart_of_accounts_id' => 5, 'debit' => 1000, 'credit' => 0], // Short-term Loans
                    ['chart_of_accounts_id' => 3, 'debit' => 0, 'credit' => 1000], // Cash on Hand
                ],
            ],
        ];

        foreach ($entries as $entry) {
            $journalEntryId = DB::table('journal_entries')->insertGetId([
                'date' => $entry['date'],
                'description' => $entry['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($entry['details'] as $detail) {
                DB::table('journal_entry_details')->insert([
                    'journal_entry_id' => $journalEntryId,
                    'chart_of_accounts_id' => $detail['chart_of_accounts_id'],
                    'debit' => $detail['debit'],
                    'credit' => $detail['credit'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
