<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="font-semibold md:text-2xl">Profit / Loss Statement</h1>
        </div>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <!-- Filter Tanggal -->
            <div class="grid w-fit gap-2">
                <Label for="reference">Tanggal Data</Label>
                <div class="flex flex-row items-center space-x-4 text-center">
                    <VueDatePicker
                        :preview-format="'dd/MMM/yyyy'"
                        :format="'dd MMMM yyyy'"
                        auto-apply
                        v-model="startDate"
                        :enable-time-picker="false"
                        :dark="mode == 'dark'"
                    ></VueDatePicker>
                    <span>s.d</span>
                    <VueDatePicker
                        :preview-format="'dd/MMM/yyyy'"
                        :format="'dd MMMM yyyy'"
                        auto-apply
                        v-model="endDate"
                        :enable-time-picker="false"
                        :dark="mode == 'dark'"
                    ></VueDatePicker>
                    <!-- Submit Button -->
                    <Button
                        type="button"
                        @click="fetchData()"
                        :disabled="onProses"
                    >
                        <span v-if="onProses" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Submit</span>
                    </Button>
                </div>
            </div>

            <div class="my-4 flex flex-col space-y-4 xl:w-2/3">
                <!-- Tabel Revenue -->

                <div>
                    <h2 class="mb-2 font-semibold">Revenue</h2>
                    <Table class="border">
                        <TableHeader class="">
                            <TableRow
                                class="text-center font-bold uppercase text-black"
                            >
                                <TableHead class="w-1/3">Nama Akun</TableHead>
                                <TableHead class="w-1/3 text-right"
                                    >Saldo</TableHead
                                >
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="asset in revenue"
                                :key="asset.name"
                            >
                                <TableCell> {{ asset.name }}</TableCell>
                                <TableCell class="border text-right">
                                    <span v-if="!onProses">
                                        {{ formatCurrency(asset.balance) }}
                                    </span>
                                    <span v-else>
                                        <Skeleton class="h-5 w-full" />
                                    </span>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                        <TableFooter>
                            <TableRow class="font-bold">
                                <TableCell class="text-right"
                                    >Total Revenue</TableCell
                                >
                                <TableCell class="text-right font-bold">
                                    <span v-if="!onProses">
                                        {{ formatCurrency(totalRevenue) }}
                                    </span>
                                    <span v-else>
                                        <Skeleton class="h-5 w-full" />
                                    </span>
                                </TableCell>
                            </TableRow>
                        </TableFooter>
                    </Table>
                </div>

                <!-- Tabel Expenses -->

                <div>
                    <h2 class="mb-2 text-xl font-semibold">Expenses</h2>
                    <Table class="border">
                        <TableHeader class="">
                            <TableRow
                                class="text-center font-bold uppercase text-black"
                            >
                                <TableHead class="w-1/3">Nama Akun</TableHead>
                                <TableHead class="w-1/3 text-right"
                                    >Saldo</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="asset in expenses"
                                :key="asset.name"
                            >
                                <TableCell> {{ asset.name }}</TableCell>
                                <TableCell class="border text-right">
                                    <span v-if="!onProses">
                                        {{ formatCurrency(asset.balance) }}
                                    </span>
                                    <span v-else>
                                        <Skeleton class="h-5 w-full" />
                                    </span>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                        <TableFooter>
                            <TableRow class="font-bold">
                                <TableCell class="text-right"
                                    >Total Liabilities</TableCell
                                >
                                <TableCell class="text-right font-bold">
                                    <span v-if="!onProses">
                                        {{ formatCurrency(totalExpenses) }}
                                    </span>
                                    <span v-else>
                                        <Skeleton class="h-5 w-full" />
                                    </span>
                                </TableCell>
                            </TableRow>
                        </TableFooter>
                    </Table>
                </div>

                <!-- Tabel Profit / Loss -->

                <Table class="border bg-gray-500 dark:bg-gray-700">
                    <TableFooter>
                        <TableRow class="font-bold">
                            <TableCell class="w-2/3 text-right uppercase"
                                >Total Profit / Loss</TableCell
                            >
                            <TableCell class="text-right font-bold">
                                <span v-if="!onProses">
                                    {{ formatCurrency(total_profit) }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" /> </span
                            ></TableCell>
                        </TableRow>
                    </TableFooter>
                </Table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import { useColorMode } from '@vueuse/core';
import { router, Link } from '@inertiajs/vue3';
import Label from '@/components/ui/label/Label.vue';
import { formatCurrency } from '@/lib/utils';
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
import { ReloadIcon } from '@radix-icons/vue';

const mode = useColorMode();
const props = defineProps({
    revenue: Array,
    expenses: Array,
    total_profit: Number,
});
const today = new Date();
const startOfYear = new Date(today.getFullYear(), 0, 1); // 1 Januari tahun ini

const startDate = ref(startOfYear);
const endDate = ref(today);

const onProses = ref(false);

const totalRevenue = computed(() => {
    return props.revenue.reduce((sum, rev) => sum + rev.balance, 0);
});

const totalExpenses = computed(() => {
    return props.expenses.reduce((sum, exp) => sum + exp.balance, 0);
});

const fetchData = () => {
    router.get(
        route('income-statement.index'),
        {
            start_date: startDate.value,
            end_date: endDate.value,
        },
        {
            preserveState: true,
            async: true,
            onStart() {
                onProses.value = true;
            },
            onFinish() {
                onProses.value = false;
            },
        },
    );
};
</script>
