<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Statement</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class=" text-gray-900">

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg">
        <!-- HEADER -->
        <div class="text-center mb-5">
            <h2 class="text-2xl font-bold uppercase">{{$company->name}}</h2>
            <p class="text-gray-600">Income Statement Report</p>
            <p class="text-gray-500 text-sm">Period: {{ $start_date }} - {{ $end_date }}</p>
        </div>

        <!-- Revenue Table -->
        <div class="mb-6">
            <h2 class="text-md font-semibold mb-2">Revenue</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 bg-white rounded-md">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Account Name</th>
                            <th class="px-4 py-2 text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenue as $item)
                        <tr class="border-t border-gray-300">
                            <td class="px-4 py-2">{{ $item['name'] }}</td>
                            <td class="px-4 py-2 text-right">{{ \App\Helpers\CurrencyHelper::formatCurrency($item['balance']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td class="px-4 py-2 text-right">Total Revenue</td>
                            <td class="px-4 py-2 text-right">
                                {{ \App\Helpers\CurrencyHelper::formatCurrency(collect($revenue)->sum('balance')) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="mb-6">
            <h2 class="text-md font-semibold mb-2">Expenses</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 bg-white rounded-md">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Account Name</th>
                            <th class="px-4 py-2 text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $item)
                        <tr class="border-t border-gray-300">
                            <td class="px-4 py-2">{{ $item['name'] }}</td>
                            <td class="px-4 py-2 text-right">{{ \App\Helpers\CurrencyHelper::formatCurrency($item['balance']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td class="px-4 py-2 text-right">Total Expenses</td>
                            <td class="px-4 py-2 text-right">
                                {{ \App\Helpers\CurrencyHelper::formatCurrency(collect($expenses)->sum('balance')) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Profit / Loss -->
        <div class="bg-gray-700 text-white p-4 rounded-md">
            <div class="flex justify-between font-bold text-md">
                <span>Total Profit / Loss</span>
                <span>
                    {{ \App\Helpers\CurrencyHelper::formatCurrency(collect($revenue)->sum('balance') - collect($expenses)->sum('balance')) }}
                </span>
            </div>
        </div>

    </div>

</body>

</html>