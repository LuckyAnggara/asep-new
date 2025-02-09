<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use App\Models\ChartOfAccount;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

use function Spatie\LaravelPdf\Support\pdf;

class ReportController extends Controller
{
    public function index(Request $request)
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

        return pdf('report', [
            'assets' => $categories[0],
            'liabilities' => $categories[1],
            'equity' => $categories[2],
        ])->download('invoice-for-april-2022.pdf');
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
}
