<html lang="en">

<head>
    <title>Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <!-- Tabel Balance Sheet -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Assets -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h2 class="text-xl font-semibold mb-2">Assets</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Akun</th>
                        <th class="border px-4 py-2">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assets['sub_category'] as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item['name'] }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($item['balance'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="font-semibold text-right mt-2">Total Assets: {{ number_format($assets['total_balance'], 2) }}</p>
        </div>

        <!-- Liabilities & Equity -->
        <div class="space-y-4">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="text-xl font-semibold mb-2">Liabilities</h2>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-4 py-2">Akun</th>
                            <th class="border px-4 py-2">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($liabilities['sub_category'] as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item['name'] }}</td>
                            <td class="border px-4 py-2 text-right">{{ number_format($item['balance'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="font-semibold text-right mt-2">Total Liabilities: {{ number_format($liabilities['total_balance'], 2) }}</p>
            </div>

            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="text-xl font-semibold mb-2">Equity</h2>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-4 py-2">Akun</th>
                            <th class="border px-4 py-2">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equity['sub_category'] as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item['name'] }}</td>
                            <td class="border px-4 py-2 text-right">{{ number_format($item['balance'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="font-semibold text-right mt-2">Total Equity: {{ number_format($equity['total_balance'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="mt-6 bg-blue-100 p-4 rounded-lg">
        <h2 class="text-lg font-semibold text-center">Total Summary</h2>
        <div class="flex justify-between">
            <p class="font-bold">Total Assets:</p>
            <p>{{ number_format($assets['total_balance'], 2) }}</p>
        </div>
        <div class="flex justify-between">
            <p class="font-bold">Total Liabilities + Equity:</p>
            <p>{{ number_format($liabilities['total_balance'] + $equity['total_balance'], 2) }}</p>
        </div>
    </div>
</body>

</html>