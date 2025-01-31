<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id', []);
        $dateRange = $request->input('date', []);

        $accounts = ChartOfAccount::all();
        $result = [];
        foreach ($id as $key => $value) {
            $account = ChartOfAccount::findOrFail($value);

            $query = JournalEntryDetail::with('journalEntry')
                ->select('journal_entry_details.*')
                ->join('journal_entries', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')
                ->where('journal_entry_details.chart_of_accounts_id', $id)
                ->orderBy('journal_entries.date', 'desc'); // Urutkan dari tanggal terbaru

            // Filter berdasarkan rentang tanggal jika ada input date
            if (!empty($dateRange) && count($dateRange) === 2) {
                $startDate = Carbon::parse($dateRange[0])->startOfDay(); // Tanggal awal
                $endDate = Carbon::parse($dateRange[1])->endOfDay(); // Tanggal akhir

                $query->whereBetween('journal_entries.date', [$startDate, $endDate]);
            }

            $transactions = $query->get();
            // Hitung balance dengan urutan kronologis (ascending)
            $sortedForBalance = $transactions->sortBy(function ($transaction) {
                return $transaction->journalEntry->date;
            });

            $balance = 0;
            $sortedForBalance->each(function ($transaction) use (&$balance) {
                $balance += $transaction->debit - $transaction->credit;
                $transaction->balance = $balance;
            });

            $account->detail = $transactions;
            $result[] = $account;
        }

        // return $result;

        // return JournalEntryDetail::join('journal_entries', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')->whereIn('journal_entry_details.chart_of_accounts_id', $id)->get();
        // // Query dengan join ke journal_entries dan sorting DESC


        return Inertia::render('accounting/ledger/Index', [
            'accounts' => $accounts,
            'transactions' => $result, // Tetap dikirim dalam urutan DESC
        ]);
    }
}
