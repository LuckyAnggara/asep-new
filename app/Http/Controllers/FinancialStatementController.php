<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FinancialStatementController extends Controller
{
    public function trialBalance(Request $request)
    {
        // Ambil tanggal periode dari request (default bulan ini)
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));


        // Ambil semua akun
        $accounts = ChartOfAccount::with(['journalEntryDetail.journalEntry'])->get();

        $trialBalance = $accounts->map(function ($account) use ($startDate, $endDate) {
            $accountId = $account->id;

            // **1. Hitung Saldo Awal dari Jurnal Opening Balance (OB)**
            $opening_balance = JournalEntryDetail::where('chart_of_accounts_id', $accountId)
                ->whereHas('journalEntry', function ($query) {
                    $query->where('reference', 'LIKE', 'OB-%');
                })
                ->sum('debit') - JournalEntryDetail::where('chart_of_accounts_id', $accountId)
                ->whereHas('journalEntry', function ($query) {
                    $query->where('reference', 'LIKE', 'OB-%');
                })
                ->sum('credit');

            // **2. Hitung Total Debit & Kredit selama Periode**
            $totalDebit = JournalEntryDetail::where('chart_of_accounts_id', $accountId)
                ->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('debit');

            $totalCredit = JournalEntryDetail::where('chart_of_accounts_id', $accountId)
                ->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('credit');

            // **3. Hitung Saldo Akhir**
            $closing_balance = $opening_balance + $totalDebit - $totalCredit;

            return [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'opening_balance' => $opening_balance,
                'debit' => $totalDebit,
                'credit' => $totalCredit,
                'closing_balance' => $closing_balance,
            ];
        });


        return Inertia::render('Accounting/FinancialStatements/TrialBalance', [
            'trialBalance' => $trialBalance,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
