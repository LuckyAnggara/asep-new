<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id', []);

        $account = ChartOfAccount::all();
        $transactions = JournalEntryDetail::with(['journalEntry' => function ($query) {
            $query->orderBy('date', 'asc'); // Sorting berdasarkan date di JournalEntry
        }])
            ->whereIn('account_id', $id)
            ->get();

        $balance = 0;
        $transactions->transform(function ($transaction) use (&$balance) {
            $balance += $transaction->debit - $transaction->credit;
            $transaction->balance = $balance;
            return $transaction;
        });
        return Inertia::render('accounting/ledger/Index', [
            'account' => $account,
            'transactions' => $transactions,
        ]);
    }
}
