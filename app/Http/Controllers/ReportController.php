<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

use function Spatie\LaravelPdf\Support\pdf;

class ReportController extends Controller
{
    public function index()
    {
        return pdf('report',)->download('invoice-for-april-2022.pdf');
        // return pdf('report', [
        //     'invoiceNumber' => '1234',
        //     'customerName' => 'Grumpy Cat',
        // ])->name('invoice-2023-04-10.pdf');
    }
}
