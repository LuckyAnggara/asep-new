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

    <!-- HEADER -->
    <div class="text-center mb-5">
        <h2 class="text-2xl font-bold uppercase">{{$company->name}}</h2>
        <p class="text-gray-600">Laporan Balance Sheet</p>
        <p class="text-gray-500 text-sm">Di cetak tanggal : {{ now()->format('d M Y') }}</p>
    </div>
    <div class="text-right my-2">
        <p class="text-gray-500 text-sm">Tanggal Data : {{ $start_date }} s.d {{$end_date}}</p>
    </div>
    <div class="flex flex-row items-start space-x-5">
        <!-- Assets Section -->
        <div class="w-1/2">
            <table class="w-full caption-bottom text-sm">
                <thead class="table-header">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 w-1/3 px-2 text-left align-middle font-medium text-muted-foreground">
                            ASSETS
                        </th>
                    </tr>
                </thead>
                <tbody class="w-full">
                    <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="w-full px-2 align-middle font-bold">
                            <div class="flex flex-row justify-between">
                                <span>{{ strtoupper($assets['name']) }}</span>
                            </div>
                        </td>
                    </tr>

                    @foreach ($assets['sub_category'] as $item)
                    <tr class="border-b font-medium transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="px-2 py-2 pl-5 align-middle font-semibold">
                            <div class="flex flex-row justify-between">
                                <span>
                                    {{ $item['code'] }} - {{ strtoupper($item['name']) }}
                                </span>
                                <span>
                                    {{ CurrencyHelper::formatCurrency($item['total_balance'], 2) }}
                                </span>
                            </div>
                            @if (!empty($item['coa']))
                            <div class="flex w-full flex-row justify-between">
                                <blockquote class="mt-1 w-full border-l-2">
                                    <ul class="my-2 ml-2 w-full list-disc pr-2">
                                        @foreach ($item['coa'] as $subItem)
                                        <li class="flex w-full flex-row justify-between font-light">
                                            <span> {{ $subItem['code'] }} - {{ $subItem['name'] }}</span>
                                            <span>{{ CurrencyHelper::formatCurrency($subItem['balance'], 2) }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </blockquote>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="w-full px-2 align-middle font-bold">
                            <div class="flex flex-row justify-between">
                                <span> TOTAL {{ strtoupper($assets['name']) }} </span>
                                <span> {{ CurrencyHelper::formatCurrency($assets['total_balance'], 2) }} </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Liabilities & Equity Section -->
        <div class="w-1/2 flex flex-col space-y-6">
            @foreach (['liabilities', 'equity'] as $type)
            <div>
                <table class="w-full caption-bottom text-sm">
                    <thead class="table-header">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <th class="h-10 w-1/3 px-2 text-left align-middle font-medium text-muted-foreground">
                                {{ strtoupper($type) }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="w-full">
                        <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="w-full px-2 align-middle font-bold">
                                <div class="flex flex-row justify-between">
                                    <span>{{ strtoupper($$type['name']) }}</span>
                                </div>
                            </td>
                        </tr>

                        @foreach ($$type['sub_category'] as $item)
                        <tr class="border-b font-medium transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="px-2 py-2 pl-5 align-middle font-semibold">
                                <div class="flex flex-row justify-between">
                                    <span>
                                        {{ $item['code'] }} - {{ strtoupper($item['name']) }}
                                    </span>
                                    <span>
                                        {{ CurrencyHelper::formatCurrency($item['total_balance'], 2) }}
                                    </span>
                                </div>
                                @if (!empty($item['coa']))
                                <div class="flex w-full flex-row justify-between">
                                    <blockquote class="mt-1 w-full border-l-2">
                                        <ul class="my-2 ml-2 w-full list-disc pr-2">
                                            @foreach ($item['coa'] as $subItem)
                                            <li class="flex w-full flex-row justify-between font-light">
                                                <span> {{ $subItem['code'] }} - {{ $subItem['name'] }}</span>
                                                <span>{{ CurrencyHelper::formatCurrency($subItem['balance'], 2) }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </blockquote>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="w-full px-2 align-middle font-bold">
                                <div class="flex flex-row justify-between">
                                    <span> TOTAL {{ strtoupper($$type['name']) }} </span>
                                    <span> {{ CurrencyHelper::formatCurrency($$type['total_balance'], 2) }} </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex flex-row items-start space-x-5">
        <table class="w-full caption-bottom text-sm">
            <tfoot class="border-t bg-muted/50 font-medium table-header">
                <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                    <td class="p-2 align-middle">TOTAL ASSETS</td>
                    <td class="p-2 align-middle text-right">
                        {{ CurrencyHelper::formatCurrency($assets['total_balance'], 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <table class="w-full caption-bottom text-sm">
            <tfoot class="border-t bg-muted/50 font-medium table-header">
                <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                    <td class="p-2 align-middle">TOTAL LIABILITIES + EQUITY</td>
                    <td class="p-2 align-middle text-right">
                        {{ CurrencyHelper::formatCurrency($liabilities['total_balance'] + $equity['total_balance'], 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>