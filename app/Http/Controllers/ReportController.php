<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use App\Models\ChartOfAccount;
use App\Models\Company;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Spatie\LaravelPdf\Facades\Pdf;

use function Spatie\LaravelPdf\Support\pdf;

class ReportController extends Controller
{
    public function balancesheet(Request $request)
    {

        // Ambil tanggal dari request
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Ambil data kategori dengan transaksi
        $categories = AccountCategory::with([
            'sub_category' => function ($query) {
                $query->orderBy('code');
            },
            'sub_category.coa' => function ($query) {
                $query->orderBy('code');
            },
            'sub_category.coa.children' => function ($query) {
                $query->orderBy('created_at');
            }
        ])->get();

        // Ambil Net Revenue dari fungsi getIncomeDetail
        $incomeStatementController = new IncomeStatementController();
        $netIncome = $incomeStatementController->getIncome($startDate, $endDate);

        // Ambil ID Retained Earnings dari perusahaan
        $company = Company::select('retained_earning_id')->first();
        $retainedEarningsId = $company->retained_earning_id ?? null;

        // Menambahkan total saldo ke setiap level
        $categories->transform(function ($category) {
            $category->total_balance = $category->sub_category->sum(function ($sub) use ($category) {
                return $sub->coa->sum(fn($coa) => $this->calculateBalance($coa, $category->normal));
            });

            $category->sub_category->transform(function ($sub) use ($category) {
                $sub->total_balance = $sub->coa->sum(fn($coa) => $this->calculateBalance($coa, $category->normal));

                $sub->coa->transform(function ($coa) use ($category) {
                    $coa->balance = $this->calculateBalance($coa, $category->normal);
                    return $coa;
                });

                return $sub;
            });

            return $category;
        });

        // Tambahkan Net Income ke akun Retained Earnings
        $retainedEarningsAccount = null;
        foreach ($categories as $category) {
            foreach ($category->sub_category as $sub) {
                foreach ($sub->coa as $coa) {
                    if ($coa->id == $retainedEarningsId) {
                        $retainedEarningsAccount = $coa;
                        break 3;
                    }
                }
            }
        }

        if ($retainedEarningsAccount) {
            $retainedEarningsAccount->balance += $netIncome['net_income'];
        }

        // Temukan kategori Ekuitas (Equity)
        $equityCategory = $categories->firstWhere('code', '3');

        // Jika ditemukan, tambahkan Retained Earnings ke total Ekuitas
        if ($equityCategory && $retainedEarningsAccount) {
            if ($retainedEarningsId !== null) {
                $equityCategory->total_balance += $retainedEarningsAccount->balance;
            }
        }

        // return View('report.financial.balancesheet', [
        //     'start_date' => Carbon::parse($startDate)->format('d M Y'),
        //     'end_date' => Carbon::parse($endDate)->format('d M Y'),
        //     'company' => Company::first(),
        //     'assets' => $categories[0],
        //     'liabilities' => $categories[1],
        //     'equity' => $categories[2],
        // ]);
        $timestamp = now()->timestamp; // Get current timestamp
        $filename = "Balance Sheet Report {$timestamp}.pdf";

        // return View('report.financial.balancesheet', [
        //     'start_date' => Carbon::parse($startDate)->format('d M Y'),
        //     'end_date' => Carbon::parse($endDate)->format('d M Y'),
        //     'company' => Company::first(),
        //     'assets' => $categories[0],
        //     'liabilities' => $categories[1],
        //     'equity' => $categories[2],
        // ]);


        return pdf('report.financial.balancesheet', [
            'start_date' => Carbon::parse($startDate)->format('d M Y'),
            'end_date' => Carbon::parse($endDate)->format('d M Y'),
            'company' => Company::first(),
            'assets' => $categories[0],
            'liabilities' => $categories[1],
            'equity' => $categories[2],
        ])->download($filename);
        // return pdf('report', [
        //     'invoiceNumber' => '1234',
        //     'customerName' => 'Grumpy Cat',
        // ])->name('invoice-2023-04-10.pdf');
    }

    private function calculateBalance($coa, $normal)
    {
        $balance = $coa->children->sum(fn($trx) => $trx->debit - $trx->credit);

        // Jika kategori akun memiliki saldo normal "credit", buat saldo tetap positif
        return $normal === 'Credit' ? -$balance : $balance;
    }

    public function incomestatement(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfYear());
        $endDate = $request->input('end_date', now()->endOfMonth());

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
        $timestamp = now()->timestamp; // Get current timestamp
        $filename = "Income Statement Report {$timestamp}.pdf";
        // return View('report.financial.incomestatement', [
        //     'start_date' => Carbon::parse($startDate)->format('d M Y'),
        //     'end_date' => Carbon::parse($endDate)->format('d M Y'),
        //     'company' => Company::first(),
        //     'expenses' => $balanceSheet['expenses'],
        //     'revenue' => $balanceSheet['revenue'],
        // ]);

        return pdf('report.financial.incomestatement', [
            'start_date' => Carbon::parse($startDate)->format('d M Y'),
            'end_date' => Carbon::parse($endDate)->format('d M Y'),
            'company' => Company::first(),
            'expenses' => $balanceSheet['expenses'],
            'revenue' => $balanceSheet['revenue'],
        ])->download($filename);
    }

    public function trialbalance(Request $request)
    {
        // Ambil tanggal periode dari request (default bulan ini)
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));


        // Ambil semua akun
        $accounts = ChartOfAccount::with(['journalEntryDetail.journalEntry'])->orderBy('code')->get();

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


        $timestamp = now()->timestamp; // Get current timestamp
        $filename = "Trial Balance Report {$timestamp}.pdf";


        // return View('report.financial.trialbalance', [
        //     'trialBalance' => $trialBalance,
        //     'start_date' => Carbon::parse($startDate)->format('d M Y'),
        //     'end_date' => Carbon::parse($endDate)->format('d M Y'),
        //     'company' => Company::first(),
        // ]);

        return pdf('report.financial.trialbalance', [
            'trialBalance' => $trialBalance,
            'start_date' => Carbon::parse($startDate)->format('d M Y'),
            'end_date' => Carbon::parse($endDate)->format('d M Y'),
            'company' => Company::first(),
        ])->download($filename);
    }

    public function journalSummary(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfYear());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $journals = JournalEntry::with('details.chartOfAccounts.subCategory')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return view('report.financial.journalsummary', [
            'start_date' => Carbon::parse($startDate)->format('d M Y'),
            'end_date' => Carbon::parse($endDate)->format('d M Y'),
            'company' => Company::first(),
            'journals' => $journals,
        ]);

        $timestamp = now()->timestamp; // Get current timestamp
        $filename = "Journal Summary Report {$timestamp}.pdf";

        return pdf('report.financial.journalsummary', [
            'start_date' => Carbon::parse($startDate)->format('d M Y'),
            'end_date' => Carbon::parse($endDate)->format('d M Y'),
            'company' => Company::first(),
            'journals' => $journals,
        ])->download($filename);
    }
}
