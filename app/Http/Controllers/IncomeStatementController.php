<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Ambil semua akun pendapatan dan beban
        $revenues = ChartOfAccount::where('account_number', '4')->pluck('id');
        $expenses = ChartOfAccount::where('account_number', '5')->pluck('id');

        // Hitung total pendapatan
        $totalRevenue = JournalEntryDetail::whereIn('chart_of_accounts_id', $revenues)->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })
            ->sum('credit');

        // Hitung total beban
        $totalExpense = JournalEntryDetail::whereIn('chart_of_accounts_id', $expenses)->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })
            ->sum('debit');

        // Hitung laba bersih
        $netIncome = $totalRevenue - $totalExpense;

        return Inertia::render('accounting/income/Index', [
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'net_income' => $netIncome,
        ]);
    }
}
