<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CashFlowController extends Controller
{

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Ambil semua transaksi terkait dengan akun yang memiliki tipe cashflow
        $cashFlow = JournalEntryDetail::with(['journalEntry', 'chartOfAccounts'])
            ->whereHas('chartOfAccounts', function ($query) {
                $query->whereNotNull('cashflow_type');
            })
            ->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->get();

        $cashFlowData = [];

        // Loop melalui setiap transaksi
        foreach ($cashFlow as $entry) {
            $type = $entry->chartOfAccounts->cashflow_type; // Ambil tipe cashflow
            $accountType = $entry->chartOfAccounts->account_number; // Ambil jenis akun (Assets, Liabilities, dll)

            if (!isset($cashFlowData[$type])) {
                $cashFlowData[$type] = ['cash_in' => 0, 'cash_out' => 0];
            }

            // Menentukan arus kas berdasarkan jenis akun
            switch ($accountType) {
                case '1':
                    // Jika aset bertambah (debit), maka itu adalah pengeluaran kas
                    if ($entry->debit > 0) {
                        $cashFlowData[$type]['cash_out'] += $entry->debit;
                    }
                    // Jika aset berkurang (kredit), itu berarti pemasukan kas
                    if ($entry->credit > 0) {
                        $cashFlowData[$type]['cash_in'] += $entry->credit;
                    }
                    break;

                case '2':
                case '3':
                case '4':
                    // Jika liabilitas/modal/pendapatan bertambah (kredit), berarti pemasukan kas
                    if ($entry->credit > 0) {
                        $cashFlowData[$type]['cash_in'] += $entry->credit;
                    }
                    // Jika berkurang (debit), berarti pengeluaran kas
                    if ($entry->debit > 0) {
                        $cashFlowData[$type]['cash_out'] += $entry->debit;
                    }
                    break;

                case '5':
                    // Jika beban bertambah (debit), itu berarti pengeluaran kas
                    if ($entry->debit > 0) {
                        $cashFlowData[$type]['cash_out'] += $entry->debit;
                    }
                    break;
            }
        }

        // Hitung total net cash flow
        $netCashFlow = [
            'total' => array_sum(array_column($cashFlowData, 'cash_in')) - array_sum(array_column($cashFlowData, 'cash_out'))
        ];



        return Inertia::render('Accounting/FinancialStatements/CashFlow', [
            'cash_flow' => $cashFlowData,
            // 'net_cash_flow' => $netCashFlow,
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
