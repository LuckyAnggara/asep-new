<?php

namespace Database\Seeders;

use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeginningBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tanggal awal tahun (misalnya 1 Januari 2025)
        $date = Carbon::create(2025, 1, 1);

        // Data untuk beginning balance
        $beginningBalances = [
            [
                'account_id' => 1, // ID akun (contoh: Bank BNI)
                'debit' => 10000000, // Saldo awal di debit
                'credit' => 0, // Tidak ada kredit
            ],
            [
                'account_id' => 2, // ID akun (contoh: Bank Mandiri)
                'debit' => 5000000, // Saldo awal di debit
                'credit' => 0, // Tidak ada kredit
            ],
            [
                'account_id' => 3, // ID akun (contoh: Cash on Hand)
                'debit' => 2000000, // Saldo awal di debit
                'credit' => 0, // Tidak ada kredit
            ],
            [
                'account_id' => 5, // ID akun (contoh: Short-term Loans)
                'debit' => 0, // Tidak ada debit
                'credit' => 7000000, // Saldo awal di kredit
            ],
        ];

        // Buat journal entry untuk beginning balance
        $journalEntry = JournalEntry::create([
            'reference' => 'BEGINNING-BALANCE-2025',
            'date' => $date,
            'description' => 'Beginning balance for the year 2025',
        ]);

        // Buat journal entry details untuk setiap akun
        foreach ($beginningBalances as $balance) {
            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_accounts_id' => $balance['account_id'],
                'debit' => $balance['debit'],
                'credit' => $balance['credit'],
            ]);
        }

        $this->command->info('Beginning balance for 2025 has been seeded.');
    }
}
