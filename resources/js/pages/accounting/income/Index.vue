<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Income Statement</h1>
        </div>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <!-- Filter Tanggal -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700"
                    >Start Date:</label
                >
                <input
                    type="date"
                    v-model="startDate"
                    @change="fetchData"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700"
                    >End Date:</label
                >
                <input
                    type="date"
                    v-model="endDate"
                    @change="fetchData"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                />
            </div>

            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2">Total Revenue</td>
                        <td class="px-4 py-2 text-right text-green-500">
                            {{ total_revenue.toLocaleString() }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">Total Expenses</td>
                        <td class="px-4 py-2 text-right text-red-500">
                            {{ total_expense.toLocaleString() }}
                        </td>
                    </tr>
                    <tr class="border-t font-bold">
                        <td class="px-4 py-2">Net Income</td>
                        <td
                            class="px-4 py-2 text-right"
                            :class="
                                net_income >= 0
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                        >
                            {{ net_income.toLocaleString() }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { useColorMode } from '@vueuse/core';
import { router, Link } from '@inertiajs/vue3';
import Label from '@/components/ui/label/Label.vue';
import { Skeleton } from '@/components/ui/skeleton';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
    TableFooter,
} from '@/components/ui/table';

const mode = useColorMode();
const props = defineProps({
    total_revenue: Number,
    total_expense: Number,
    net_income: Number,
});

const startDate = ref(props.startDate);
const endDate = ref(props.endDate);

const onProses = ref(false);
const date = ref(props.date);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(value);
};

const fetchData = () => {
    router.get(
        route('income-statement'),
        {
            start_date: startDate.value,
            end_date: endDate.value,
        },
        {
            preserveState: true,
        },
    );
};
</script>
