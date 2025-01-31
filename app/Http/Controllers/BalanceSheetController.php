<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BalanceSheetController extends Controller
{

    public function index(Request $request)
    {
        // Ambil tanggal dari request (jika ada)
        $date = $request->input('date');

        // Ambil data Balance Sheet
        $balanceSheet = $this->getBalanceSheet($date);

        return Inertia::render('accounting/balancesheet/Index', [
            'balanceSheet' => $balanceSheet,
            'date' => $date,
        ]);
    }


    public function getBalanceSheet($date = null)
    {
        // Jika tanggal tidak diberikan, gunakan tanggal hari ini
        $endDate = $date ? Carbon::parse($date) : Carbon::now();

        // Ambil semua akun
        $accounts = ChartOfAccount::with(['subCategory.category'])->get();

        // Hitung saldo setiap akun
        $balances = [];
        foreach ($accounts as $account) {
            $debit = JournalEntryDetail::where('chart_of_accounts_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($endDate) {
                    $query->where('date', '<=', $endDate);
                })
                ->sum('debit');

            $credit = JournalEntryDetail::where('chart_of_accounts_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($endDate) {
                    $query->where('date', '<=', $endDate);
                })
                ->sum('credit');

            $balances[$account->id] = $debit - $credit;
        }

        // Kelompokkan akun berdasarkan kategori
        $balanceSheet = [
            'assets' => [],
            'liabilities' => [],
            'equity' => [],
        ];

        foreach ($accounts as $account) {
            $categoryType = $account->subCategory->category->name;

            if (in_array($categoryType, ['Assets'])) {
                $balanceSheet['assets'][] = [
                    'name' => $account->name,
                    'balance' => $balances[$account->id],
                ];
            } elseif (in_array($categoryType, ['Liabilities'])) {
                $balanceSheet['liabilities'][] = [
                    'name' => $account->name,
                    'balance' => $balances[$account->id],
                ];
            } elseif (in_array($categoryType, ['Equity'])) {
                $balanceSheet['equity'][] = [
                    'name' => $account->name,
                    'balance' => $balances[$account->id],
                ];
            }
        }

        return $balanceSheet;
    }
}
