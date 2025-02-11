<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .page-break {
                page-break-before: always;
                /* Forces a page break before this element */
            }

            .print-area {
                margin: 0;
                padding: 2mm;
                page-break-after: always;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class=" text-gray-900">

    @php
    use App\Helpers\CurrencyHelper;
    @endphp

    <div class="max-w-4xl mx-auto my-10 bg-white p-6">

        <div class="text-center mb-5">
            <h2 class="text-2xl font-bold uppercase">{{$company->name}}</h2>
            <p class="text-gray-600">Balance Sheet Report</p>
            <p class="text-gray-500 text-sm">Period: {{ $start_date }} - {{ $end_date }}</p>
        </div>


        <!-- ASSETS TABLE -->
        <div class="mb-6">
            <h2 class="text-md font-semibold mb-2">Assets</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 bg-white rounded-md">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Account Name</th>
                            <th class="px-4 py-2 text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assets['sub_category'] as $item)
                        <tr class="border-t border-gray-300">
                            <td class="px-4 py-2">{{ $item['code'] }} - {{ ($item['name']) }}</td>
                            <td class="px-4 py-2 text-right">
                                {{ CurrencyHelper::formatCurrency($item['total_balance'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td class="px-4 py-2 text-right">Total Assets</td>
                            <td class="px-4 py-2 text-right">
                                {{ CurrencyHelper::formatCurrency($assets['total_balance'], 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- LIABILITIES TABLE -->
        <div class="mb-6">
            <h2 class="text-md font-semibold mb-2">Liabilities</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 bg-white rounded-md">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Account Name</th>
                            <th class="px-4 py-2 text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($liabilities['sub_category'] as $item)
                        <tr class="border-t border-gray-300">
                            <td class="px-4 py-2">{{ $item['code'] }} - {{ ($item['name']) }}</td>
                            <td class="px-4 py-2 text-right">
                                {{ CurrencyHelper::formatCurrency($item['total_balance'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td class="px-4 py-2 text-right">Total Liabilities</td>
                            <td class="px-4 py-2 text-right">
                                {{ CurrencyHelper::formatCurrency($liabilities['total_balance'], 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- EQUITY TABLE -->
        <div class="mb-6">
            <h2 class="text-md font-semibold mb-2">Equity</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 bg-white rounded-md">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Account Name</th>
                            <th class="px-4 py-2 text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equity['sub_category'] as $item)
                        <tr class="border-t border-gray-300">
                            <td class="px-4 py-2">{{ $item['code'] }} - {{ ($item['name']) }}</td>
                            <td class="px-4 py-2 text-right">
                                {{ CurrencyHelper::formatCurrency($item['total_balance'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td class="px-4 py-2 text-right">Total Equity</td>
                            <td class="px-4 py-2 text-right">
                                {{ CurrencyHelper::formatCurrency($equity['total_balance'], 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="page-break my-8"></div>
        <!-- TOTAL BALANCE SHEET SUMMARY -->
        <div class="bg-gray-700 text-white p-4 rounded-md">
            <div class="flex justify-between font-bold text-md">
                <span>Total Assets</span>
                <span>{{ CurrencyHelper::formatCurrency($assets['total_balance'], 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-md">
                <span>Total Liabilities + Equity</span>
                <span>{{ CurrencyHelper::formatCurrency($liabilities['total_balance'] + $equity['total_balance'], 2) }}</span>
            </div>
        </div>



    </div>

</body>

</html>