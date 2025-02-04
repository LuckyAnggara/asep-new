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
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Ambil data Balance Sheet
        $accounts = ChartOfAccount::with(['subCategory.category'])->get();
        $incomeStatementController = new IncomeStatementController();
        $income = $incomeStatementController->getIncome($startDate, $endDate);
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

            // Hitung saldo berdasarkan jenis akun
            $categoryType = $account->subCategory->category->normal;

            // if (in_array($categoryType, ['debit'])) {
            if ($categoryType == 'Debit') {
                // Aset: Debit - Credit (saldo normal di debit)
                $balances[$account->id] = $debit - $credit;
            } else {
                // Liabilitas dan Ekuitas: Credit - Debit (saldo normal di kredit)
                $balances[$account->id] = $credit - $debit;
            }
        }

        // Kelompokkan akun berdasarkan kategori
        $balanceSheet = [
            'assets' => [],
            'liabilities' => [],
            'equity' => [],
        ];

        foreach ($accounts as $account) {
            $categoryType = $account->account_number;

            if (in_array($categoryType, [1])) {
                $balanceSheet['assets'][] = [
                    'name' => $account->name,
                    'balance' => $balances[$account->id],
                ];
            } elseif (in_array($categoryType, [2])) {
                $balanceSheet['liabilities'][] = [
                    'name' => $account->name,
                    'balance' => $balances[$account->id],
                ];
            } elseif (in_array($categoryType, [3])) {
                $balanceSheet['equity'][] = [
                    'name' => $account->name,
                    'balance' => $balances[$account->id],
                ];
            }
        }

        $balanceSheet['equity'][] = [
            'name' => 'Profit / Loss',
            'balance' => $income['net_income']
        ];


        return Inertia::render('Accounting/FinancialStatements/BalanceSheet', [
            'balanceSheet' => $balanceSheet,

        ]);
    }


    public function getBalanceSheet($date = null, $tahun = null)
    {
        // Jika tanggal tidak diberikan, gunakan tanggal hari ini
        $endDate = $date ? Carbon::parse($date) : Carbon::now();
        if ($tahun) { // Jika $tahun diisi (tidak kosong)
            $startDate = Carbon::createFromDate($tahun, 1, 1); // Buat objek Carbon untuk 1 Januari tahun tersebut
        } else { // Jika $tahun tidak diisi
            $startDate = Carbon::now()->startOfYear(); // Gunakan 1 Januari tahun текущий
        }



        return $balanceSheet;
    }
}
