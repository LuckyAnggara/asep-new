<template>
    <AuthenticatedLayout>
        <div>
            <h1 class="mb-4 text-2xl font-bold">Balance Sheet</h1>

            <!-- Filter Tanggal -->
            <div class="mb-4">
                <label
                    for="date"
                    class="block text-sm font-medium text-gray-700"
                    >Date:</label
                >
                <input
                    type="date"
                    v-model="date"
                    @change="fetchBalanceSheet"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                />
            </div>

            <!-- Tabel Aset -->
            <div class="mb-8">
                <h2 class="mb-2 text-xl font-semibold">Assets</h2>
                <table class="min-w-full border border-gray-200 bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Account</th>
                            <th class="border px-4 py-2">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="asset in balanceSheet.assets"
                            :key="asset.name"
                        >
                            <td class="border px-4 py-2">{{ asset.name }}</td>
                            <td class="border px-4 py-2 text-right">
                                {{ formatCurrency(asset.balance) }}
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2 font-semibold">
                                Total Assets
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{ formatCurrency(totalAssets) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabel Liabilitas dan Ekuitas -->
            <div class="grid grid-cols-2 gap-8">
                <!-- Liabilitas -->
                <div>
                    <h2 class="mb-2 text-xl font-semibold">Liabilities</h2>
                    <table class="min-w-full border border-gray-200 bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2">Account</th>
                                <th class="border px-4 py-2">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="liability in balanceSheet.liabilities"
                                :key="liability.name"
                            >
                                <td class="border px-4 py-2">
                                    {{ liability.name }}
                                </td>
                                <td class="border px-4 py-2 text-right">
                                    {{ formatCurrency(liability.balance) }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="border px-4 py-2 font-semibold">
                                    Total Liabilities
                                </td>
                                <td
                                    class="border px-4 py-2 text-right font-semibold"
                                >
                                    {{ formatCurrency(totalLiabilities) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Ekuitas -->
                <div>
                    <h2 class="mb-2 text-xl font-semibold">Equity</h2>
                    <table class="min-w-full border border-gray-200 bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2">Account</th>
                                <th class="border px-4 py-2">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="equity in balanceSheet.equity"
                                :key="equity.name"
                            >
                                <td class="border px-4 py-2">
                                    {{ equity.name }}
                                </td>
                                <td class="border px-4 py-2 text-right">
                                    {{ formatCurrency(equity.balance) }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="border px-4 py-2 font-semibold">
                                    Total Equity
                                </td>
                                <td
                                    class="border px-4 py-2 text-right font-semibold"
                                >
                                    {{ formatCurrency(totalEquity) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Liabilitas dan Ekuitas -->
            <div class="mt-8">
                <table class="min-w-full border border-gray-200 bg-white">
                    <tbody>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2 font-semibold">
                                Total Liabilities and Equity
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{
                                    formatCurrency(
                                        totalLiabilities + totalEquity,
                                    )
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    balanceSheet: Object,
    date: String,
});

const date = ref(props.date);

const totalAssets = computed(() => {
    return props.balanceSheet.assets.reduce(
        (sum, asset) => sum + asset.balance,
        0,
    );
});

const totalLiabilities = computed(() => {
    return props.balanceSheet.liabilities.reduce(
        (sum, liability) => sum + liability.balance,
        0,
    );
});

const totalEquity = computed(() => {
    return props.balanceSheet.equity.reduce(
        (sum, equity) => sum + equity.balance,
        0,
    );
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(value);
};

const fetchBalanceSheet = () => {
    router.get(
        route('balance-sheet.index'),
        { date: date.value },
        {
            preserveState: true,
        },
    );
};
</script>
