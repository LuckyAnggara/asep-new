<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>


    <style>
        @media print {
            .print-hidden {
                display: none;
            }
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }


        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            font-size: 12px;
        }
    </style>
    @googlefonts
</head>

<body class="text-gray-900 p-6">

    <div class="max-w-5xl mx-auto bg-white p-6 ">

        <div class="text-center mb-5">
            <h2 class="text-2xl font-bold uppercase">{{$company->name}}</h2>
            <p class="text-gray-600">Journal Summary</p>
            <p class="text-gray-500 text-sm">Period: {{ $start_date }} - {{ $end_date }}</p>
        </div>

        @php
        use App\Helpers\CurrencyHelper;
        @endphp

        <div class="mb-6">
            <table class="w-full border border-gray-300 bg-white ">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Code Account</th>

                        <th class="px-4 py-2 text-left">Transcation Date</th>
                        <th class="px-4 py-2 text-left flex flex-col">
                            <span>Category Account</span>
                            <span>Ledger Account</span>
                        </th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-right">Debit</th>
                        <th class="px-4 py-2 text-right">Credit</th>
                        <th class="px-4 py-2 text-right">Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($journals as $journal)
                    @foreach ($journal->details as $detail)
                    <tr class="border-t border-gray-300">
                        <td class="px-4 py-2">{{ $detail->chartOfAccounts->code }}</td>

                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}</td>
                        <td class="px-4 py-2 flex flex-col"><span class="font-semibold">{{ $detail->chartOfAccounts->subCategory->name }}</span><span>{{ $detail->chartOfAccounts->name }}</span></td>
                        <td class="px-4 py-2">{{ $journal->description }}</td>
                        <td class="px-4 py-2 text-right">
                            {{ $detail->debit == 0 ? '-' : CurrencyHelper::formatCurrency($detail->debit, 2) }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            {{ $detail->credit == 0 ? '-' : CurrencyHelper::formatCurrency($detail->credit, 2) }}
                        </td>
                        <td class="px-4 py-2 text-right">{{ $journal->reference }}</td>
                    </tr>
                    @endforeach
                    <tr class="border-t border-gray-300 bg-gray-100 font-bold">
                        <td colspan="5" class="px-4 py-2 text-right">Total G/L Balance</td>
                        <td class="px-4 py-2 text-right">
                            {{ CurrencyHelper::formatCurrency($journal->details->sum('debit'), 2) }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            {{ CurrencyHelper::formatCurrency($journal->details->sum('credit'), 2) }}
                        </td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-700 text-white font-bold">
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-right">Total Net Balance</td>
                        <td class="px-4 py-2 text-right">
                            {{ CurrencyHelper::formatCurrency($journals->flatMap->details->sum('debit'), 2) }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            {{ CurrencyHelper::formatCurrency($journals->flatMap->details->sum('credit'), 2) }}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-right">Total Net Change</td>
                        <td colspan="2" class="px-4 py-2 text-right">
                            {{ CurrencyHelper::formatCurrency($journals->flatMap->details->sum('debit') - $journals->flatMap->details->sum('credit'), 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

</body>

</html>