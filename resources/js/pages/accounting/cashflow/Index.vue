<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">
                Cash Flow Statement
            </h1>
        </div>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <!-- Filter Tanggal -->
            <div class="mb-4">
                <label
                    for="start_date"
                    class="block text-sm font-medium text-gray-700"
                    >Start Date:</label
                >
                <input
                    type="date"
                    v-model="startDate"
                    @change="fetchCashFlow"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                />
            </div>
            <div class="mb-4">
                <label
                    for="end_date"
                    class="block text-sm font-medium text-gray-700"
                    >End Date:</label
                >
                <input
                    type="date"
                    v-model="endDate"
                    @change="fetchCashFlow"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                />
            </div>

            <!-- Aktivitas Operasi -->
            <div class="mb-8">
                <h2 class="mb-2 text-xl font-semibold">Operating Activities</h2>
                <table class="min-w-full border border-gray-200 bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Account</th>
                            <th class="border px-4 py-2">Inflow</th>
                            <th class="border px-4 py-2">Outflow</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="activity in cashFlow.operating"
                            :key="activity.name"
                        >
                            <td class="border px-4 py-2">
                                {{ activity.name }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ formatCurrency(activity.inflow) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ formatCurrency(activity.outflow) }}
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2 font-semibold">
                                Total Operating Activities
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{ formatCurrency(totalOperatingInflow) }}
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{ formatCurrency(totalOperatingOutflow) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Aktivitas Investasi -->
            <div class="mb-8">
                <h2 class="mb-2 text-xl font-semibold">Investing Activities</h2>
                <table class="min-w-full border border-gray-200 bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Account</th>
                            <th class="border px-4 py-2">Inflow</th>
                            <th class="border px-4 py-2">Outflow</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="activity in cashFlow.investing"
                            :key="activity.name"
                        >
                            <td class="border px-4 py-2">
                                {{ activity.name }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ formatCurrency(activity.inflow) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ formatCurrency(activity.outflow) }}
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2 font-semibold">
                                Total Investing Activities
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{ formatCurrency(totalInvestingInflow) }}
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{ formatCurrency(totalInvestingOutflow) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Aktivitas Pendanaan -->
            <div class="mb-8">
                <h2 class="mb-2 text-xl font-semibold">Financing Activities</h2>
                <table class="min-w-full border border-gray-200 bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Account</th>
                            <th class="border px-4 py-2">Inflow</th>
                            <th class="border px-4 py-2">Outflow</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="activity in cashFlow.financing"
                            :key="activity.name"
                        >
                            <td class="border px-4 py-2">
                                {{ activity.name }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ formatCurrency(activity.inflow) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ formatCurrency(activity.outflow) }}
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2 font-semibold">
                                Total Financing Activities
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{ formatCurrency(totalFinancingInflow) }}
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{ formatCurrency(totalFinancingOutflow) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Total Arus Kas -->
            <div class="mt-8">
                <table class="min-w-full border border-gray-200 bg-white">
                    <tbody>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2 font-semibold">
                                Net Cash Flow
                            </td>
                            <td
                                class="border px-4 py-2 text-right font-semibold"
                            >
                                {{ formatCurrency(netCashFlow) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    cashFlow: Object,
    startDate: String,
    endDate: String,
});

const startDate = ref(props.startDate);
const endDate = ref(props.endDate);

const totalOperatingInflow = computed(() => {
    return props.cashFlow.operating.reduce(
        (sum, activity) => sum + activity.inflow,
        0,
    );
});

const totalOperatingOutflow = computed(() => {
    return props.cashFlow.operating.reduce(
        (sum, activity) => sum + activity.outflow,
        0,
    );
});

const totalInvestingInflow = computed(() => {
    return props.cashFlow.investing.reduce((sum, activity) => {
        const inflow = parseFloat(activity.inflow) || 0; // Pastikan inflow adalah angka
        return sum + inflow;
    }, 0);
});

const totalInvestingOutflow = computed(() => {
    return props.cashFlow.investing.reduce((sum, activity) => {
        const outflow = parseFloat(activity.outflow) || 0; // Pastikan outflow adalah angka
        return sum + outflow;
    }, 0);
});

const totalFinancingInflow = computed(() => {
    return props.cashFlow.financing.reduce((sum, activity) => {
        const inflow = parseFloat(activity.inflow) || 0; // Pastikan inflow adalah angka
        return sum + inflow;
    }, 0);
});

const totalFinancingOutflow = computed(() => {
    return props.cashFlow.financing.reduce((sum, activity) => {
        const outflow = parseFloat(activity.outflow) || 0; // Pastikan outflow adalah angka
        return sum + outflow;
    }, 0);
});

const netCashFlow = computed(() => {
    return (
        totalOperatingInflow.value -
        totalOperatingOutflow.value +
        (totalInvestingInflow.value - totalInvestingOutflow.value) +
        (totalFinancingInflow.value - totalFinancingOutflow.value)
    );
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(value);
};

const fetchCashFlow = () => {
    router.get(
        route('cash-flow.index'),
        { start_date: startDate.value, end_date: endDate.value },
        {
            preserveState: true,
        },
    );
};
</script>
