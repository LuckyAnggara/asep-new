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
        $startDate = $request->input('start_date', now()->startOfYear());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = $this->getIncomeDetail($startDate, $endDate);
        // return $this->getIncomeDetail($startDate, $endDate);
        return Inertia::render('accounting/income/Index', $data);
    }

    public function getIncome($startDate = null, $endDate = null)
    {
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

        return [
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'net_income' => $netIncome,
        ];
    }

    public function getIncomeDetail($startDate = null, $endDate = null)
    {
        // Ambil semua akun pendapatan dan beban
        $accounts = ChartOfAccount::whereIn('account_number', ['4', '5'])->get();

        // Hitung saldo setiap akun
        $balances = [];
        $totalProfit = 0;
        foreach ($accounts as $account) {
            $debit = JournalEntryDetail::where('chart_of_accounts_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('debit');

            $credit = JournalEntryDetail::where('chart_of_accounts_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('credit');

            // Hitung saldo berdasarkan jenis akun
            $categoryType = $account->account_number;

            // if (in_array($categoryType, ['debit'])) {
            if ($categoryType == 5) {
                // Aset: Debit - Credit (saldo normal di debit)
                $balances[$account->id] = $debit - $credit;
                $totalProfit = $totalProfit - $balances[$account->id];
            } else if ($categoryType == 4) {
                // Liabilitas dan Ekuitas: Credit - Debit (saldo normal di kredit)
                $balances[$account->id] = $credit - $debit;
                $totalProfit = $totalProfit + $balances[$account->id];
            }
        }

        // Kelompokkan akun berdasarkan kategori
        $balanceSheet = [
            'revenue' => [],
            'expenses' => [],
        ];

        foreach ($accounts as $account) {
            $categoryType = $account->account_number;

            if (in_array($categoryType, [4])) {
                $balanceSheet['revenue'][] = [
                    'name' => $account->name,
                    'balance' => $balances[$account->id],
                ];
            } elseif (in_array($categoryType, [5])) {
                $balanceSheet['expenses'][] = [
                    'name' => $account->name,
                    'balance' => $balances[$account->id],
                ];
            }
        }
        $balanceSheet['total_profit'] = $totalProfit;
        return $balanceSheet;
    }
}
