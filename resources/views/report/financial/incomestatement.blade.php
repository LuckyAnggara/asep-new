<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .table-header {
            background-color: #f3f3f3;
            font-weight: bold;
        }

        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            font-size: 12px;
        }

        .table-header {
            background-color: #1f2937;
            color: white;
            font-weight: bold;
        }

        .table-striped:nth-child(even) {
            background-color: #f3f4f6;
        }
    </style>
    @googlefonts
</head>

<body class="p-5">
    @php
    use App\Helpers\CurrencyHelper;
    @endphp

    <div class="text-center mb-5">
        <h2 class="text-2xl font-bold uppercase">{{ $company->name }}</h2>
        <p class="text-gray-600">Laporan Balance Sheet</p>
        <p class="text-gray-500 text-sm">Di cetak tanggal : {{ now()->formatCurrency('d M Y') }}</p>
    </div>
    <div class="text-right my-2">
        <p class="text-gray-500 text-sm">Tanggal Data : {{ $start_date }} s.d {{ $end_date }}</p>
    </div>
    <div class="my-4 flex flex-col space-y-4 xl:w-2/3">
        <div>
            <h2 class="mb-2 text-lg font-semibold uppercase text-gray-700 dark:text-gray-300">
                Revenue
            </h2>
            <table class="w-full border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-200 dark:bg-gray-800">
                    <tr class="text-center font-bold uppercase text-black dark:text-white">
                        <th class="w-2/3 px-4 py-2 text-left">Nama Akun</th>
                        <th class="w-1/3 px-4 py-2 text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenue as $item)
                    <tr class="border-b border-gray-300 dark:border-gray-600">
                        <td class="px-4 py-2">{{ $item['name'] }}</td>
                        <td class="px-4 py-2 text-right">
                            {{ CurrencyHelper::formatCurrency($item->balance ?? 0) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 dark:bg-gray-700 font-bold">
                    <tr>
                        <td class="px-4 py-2 text-right">Total Revenue</td>
                        <td class="px-4 py-2 text-right">
                            <!-- {{ CurrencyHelper::formatCurrency($totalRevenue) }} -->
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div>
            <h2 class="mb-2 text-lg font-semibold uppercase text-gray-700 dark:text-gray-300">
                Expenses
            </h2>
            <table class="w-full border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-200 dark:bg-gray-800">
                    <tr class="text-center font-bold uppercase text-black dark:text-white">
                        <th class="w-2/3 px-4 py-2 text-left">Nama Akun</th>
                        <th class="w-1/3 px-4 py-2 text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $item)
                    <tr class="border-b border-gray-300 dark:border-gray-600">
                        <td class="px-4 py-2">{{ $item['name'] }}</td>
                        <td class="px-4 py-2 text-right">
                            {{ CurrencyHelper::formatCurrency($item->balance ?? 0) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 dark:bg-gray-700 font-bold">
                    <tr>
                        <td class="px-4 py-2 text-right">Total Expenses</td>
                        <td class="px-4 py-2 text-right">
                            <!-- {{ CurrencyHelper::formatCurrency($totalExpenses) }} -->
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <table class="w-full border border-gray-300 dark:border-gray-600 bg-gray-500 dark:bg-gray-700 text-white">
            <tfoot class="font-bold">
                <tr>
                    <td class="px-4 py-2 text-right uppercase">Total Profit / Loss</td>
                    <td class="px-4 py-2 text-right">
                        <!-- {{ CurrencyHelper::formatCurrency($total_profit) }} -->
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>

</html>