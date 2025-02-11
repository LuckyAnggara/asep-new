<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial Balance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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

<body class=" text-gray-900">

    @php
    use App\Helpers\CurrencyHelper;
    @endphp

    <div class="max-w-5xl mx-auto my-10 bg-white p-6 ">
        <div class="text-center mb-5">
            <h2 class="text-2xl font-bold uppercase">{{$company->name}}</h2>
            <p class="text-gray-600">Trial Balance</p>
            <p class="text-gray-500 text-sm">Period: {{ $start_date }} - {{ $end_date }}</p>
        </div>


        <!-- TRIAL BALANCE TABLE -->
        <div class="mb-6">
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 bg-white ">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Account Code</th>
                            <th class="px-4 py-2 text-left">Account Name</th>
                            <th class="px-4 py-2 text-right">Opening Balance</th>
                            <th class="px-4 py-2 text-right">Debit</th>
                            <th class="px-4 py-2 text-right">Credit</th>
                            <th class="px-4 py-2 text-right">Closing Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trialBalance as $account)
                        <tr class="border-t border-gray-300">
                            <td class="px-4 py-2">{{ $account['account_code'] }}</td>
                            <td class="px-4 py-2 ">
                                {{ $account['account_name'] }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ $account['opening_balance'] == 0 ? '-' : CurrencyHelper::formatCurrency($account['opening_balance'], 2) }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ $account['debit'] == 0 ? '-' : CurrencyHelper::formatCurrency($account['debit'], 2) }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ $account['credit'] == 0 ? '-' : CurrencyHelper::formatCurrency($account['credit'], 2) }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ $account['closing_balance'] == 0 ? '-' : CurrencyHelper::formatCurrency($account['closing_balance'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-right">Total</td>
                            <td class="px-4 py-2 text-right"></td>
                            <td class="px-4 py-2 text-right">
                                {{ CurrencyHelper::formatCurrency(collect($trialBalance)->sum('debit'), 2) }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ CurrencyHelper::formatCurrency(collect($trialBalance)->sum('credit'), 2) }}
                            </td>
                            <td class="px-4 py-2 text-right"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


    </div>



</body>

</html>