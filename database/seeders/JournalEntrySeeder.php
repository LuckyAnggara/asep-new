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
                    ['account_id' => 10, 'debit' => 500, 'credit' => 0], // Supplies
                    ['account_id' => 17, 'debit' => 0, 'credit' => 500], // Accounts Payable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(9),
                'description' => 'Payment to Supplier',
                'details' => [
                    ['account_id' => 17, 'debit' => 500, 'credit' => 0], // Accounts Payable
                    ['account_id' => 3, 'debit' => 0, 'credit' => 500], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(8),
                'description' => 'Client Payment for Services',
                'details' => [
                    ['account_id' => 3, 'debit' => 2000, 'credit' => 0], // Cash
                    ['account_id' => 4, 'debit' => 0, 'credit' => 2000], // Accounts Receivable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(7),
                'description' => 'Rent Payment',
                'details' => [
                    ['account_id' => 19, 'debit' => 1000, 'credit' => 0], // Rent Expense
                    ['account_id' => 3, 'debit' => 0, 'credit' => 1000], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(6),
                'description' => 'Salary Payment',
                'details' => [
                    ['account_id' => 9, 'debit' => 3000, 'credit' => 0], // Salaries Expense
                    ['account_id' => 3, 'debit' => 0, 'credit' => 3000], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(5),
                'description' => 'Purchase of Equipment',
                'details' => [
                    ['account_id' => 18, 'debit' => 5000, 'credit' => 0], // Equipment
                    ['account_id' => 2, 'debit' => 0, 'credit' => 5000], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(4),
                'description' => 'Owner Investment',
                'details' => [
                    ['account_id' => 3, 'debit' => 7000, 'credit' => 0], // Cash
                    ['account_id' => 7, 'debit' => 0, 'credit' => 7000], // Owner’s Capital
                ],
            ],
            [
                'date' => Carbon::now()->subDays(3),
                'description' => 'Service Revenue Earned',
                'details' => [
                    ['account_id' => 4, 'debit' => 5000, 'credit' => 0], // Accounts Receivable
                    ['account_id' => 8, 'debit' => 0, 'credit' => 5000], // Service Revenue
                ],
            ],
            [
                'date' => Carbon::now()->subDays(2),
                'description' => 'Partial Payment Received from Client',
                'details' => [
                    ['account_id' => 3, 'debit' => 2500, 'credit' => 0], // Cash
                    ['account_id' => 4, 'debit' => 0, 'credit' => 2500], // Accounts Receivable
                ],
            ],
            [
                'date' => Carbon::now()->subDays(1),
                'description' => 'Utility Bill Payment',
                'details' => [
                    ['account_id' => 20, 'debit' => 700, 'credit' => 0], // Utilities Expense (bisa diganti dengan Utilities Expense)
                    ['account_id' => 3, 'debit' => 0, 'credit' => 700], // Cash
                ],
            ],
            [
                'date' => Carbon::now()->subDays(1),
                'description' => 'Utility Bill Payment',
                'details' => [
                    ['account_id' => 20, 'debit' => 700, 'credit' => 0], // Biaya Utilitas
                    ['account_id' => 3,  'debit' => 0, 'credit' => 700], // Kas
                ],
            ],
            [
                'date' => Carbon::now()->subDays(2),
                'description' => 'Salary Payment',
                'details' => [
                    ['account_id' => 9, 'debit' => 5000, 'credit' => 0], // Gaji
                    ['account_id' => 3, 'debit' => 0, 'credit' => 5000], // Kas
                ],
            ],
            [
                'date' => Carbon::now()->subDays(3),
                'description' => 'Service Revenue Received',
                'details' => [
                    ['account_id' => 3, 'debit' => 3000, 'credit' => 0], // Kas
                    ['account_id' => 8, 'debit' => 0, 'credit' => 3000], // Pendapatan Jasa
                ],
            ],
            [
                'date' => Carbon::now()->subDays(4),
                'description' => 'Purchase Office Supplies',
                'details' => [
                    ['account_id' => 10, 'debit' => 150, 'credit' => 0], // Perlengkapan Kantor
                    ['account_id' => 3,  'debit' => 0, 'credit' => 150], // Kas
                ],
            ],
            [
                'date' => Carbon::now()->subDays(5),
                'description' => 'Loan Received from Bank',
                'details' => [
                    ['account_id' => 1, 'debit' => 10000, 'credit' => 0], // Bank Mandiri
                    ['account_id' => 5, 'debit' => 0, 'credit' => 10000], // Hutang Jangka Pendek
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
                    'account_id' => $detail['account_id'],
                    'debit' => $detail['debit'],
                    'credit' => $detail['credit'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
