<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal dari request (jika ada)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil data Cash Flow
        $cashFlow = $this->getCashFlow($startDate, $endDate);
        return Inertia::render('accounting/cashflow/Index', [
            'cashFlow' => $cashFlow,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function getCashFlow($startDate = null, $endDate = null)
    {
        // Jika tanggal tidak diberikan, gunakan periode bulan ini
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        // Ambil semua akun
        $accounts = ChartOfAccount::with(['subCategory.category'])->get();

        // Inisialisasi array untuk menyimpan arus kas
        $cashFlow = [
            'operating' => [],
            'investing' => [],
            'financing' => [],
        ];

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

            $categoryType = $account->subCategory->category->name;

            if (in_array($categoryType, ['Revenue', 'Expenses'])) {
                // Aktivitas Operasi
                $cashFlow['operating'][] = [
                    'name' => $account->name,
                    'inflow' => $credit ?? 0,
                    'outflow' => $debit ?? 0,
                ];
            } elseif (in_array($categoryType, ['Assets'])) {
                // Aktivitas Investasi
                $cashFlow['investing'][] = [
                    'name' => $account->name,
                    'inflow' => $credit ?? 0,
                    'outflow' => $debit ?? 0,
                ];
            } elseif (in_array($categoryType, ['Liabilities', 'Equity'])) {
                // Aktivitas Pendanaan
                $cashFlow['financing'][] = [
                    'name' => $account->name,
                    'inflow' => $credit ?? 0,
                    'outflow' => $debit ?? 0,
                ];
            }
        }

        return $cashFlow;
    }
}
