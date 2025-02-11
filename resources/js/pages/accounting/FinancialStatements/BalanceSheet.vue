<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="font-semibold md:text-2xl">Balance Sheet</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <!-- Filter Tanggal -->

            <div class="mb-4 flex items-center gap-4">
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
                    <Button type="button" @click="fetchData()" :disabled="onProses">
                        <span v-if="onProses" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Submit</span>
                    </Button>
                    <a :href="route('report-balance-sheet')" target="_blank" as="button" class="">
                        <Button type="button" :disabled="onProses">
                            <span v-if="onProses" class="flex">
                                <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                Please wait
                            </span>

                            <span v-else>Report</span>
                        </Button>
                    </a>
                </div>
            </div>

            <!-- Alert -->
            <Alert variant="destructive" v-if="assets.total_balance !== liabilities.total_balance + equity.total_balance">
                <AlertCircle class="h-4 w-4" />
                <AlertTitle>Balance Sheet Tidak Seimbang!</AlertTitle>
                <AlertDescription>
                    Laporan keuangan menunjukkan ketidakseimbangan. ⚠️ Kemungkinan penyebab: <strong>Akun Retained Earnings belum ditentukan.</strong>

                    <Link :href="route('settings.index')" class="font-bold text-primary underline"> Klik di sini </Link> untuk mengatur akun dan memperbaiki
                    ketidakseimbangan.
                </AlertDescription>
            </Alert>
            <!-- Alert -->
            <div class="flex flex-row items-start space-x-5">
                <!-- Tabel Aset -->

                <div class="my-4 flex w-2/4 flex-col">
                    <table class="w-full caption-bottom text-sm">
                        <thead class="border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <th
                                    class="h-10 w-1/3 px-2 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5"
                                >
                                    Assets
                                </th>
                            </tr>
                        </thead>
                        <tbody class="w-full [&_tr:last-child]:border-0">
                            <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="w-full px-2 align-middle font-bold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                    <div class="flex flex-row justify-between">
                                        <span>
                                            {{ assets.name.toUpperCase() }}
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            <tr
                                class="border-b font-medium transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted"
                                v-for="item in assets.sub_category"
                                :key="item.id"
                            >
                                <td class="px-2 py-2 pl-5 align-middle font-semibold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                    <div class="flex flex-row justify-between">
                                        <span>
                                            {{ item.code }} -
                                            {{ item.name.toUpperCase() }}
                                        </span>
                                        <span>
                                            {{ formatCurrency(item.total_balance) }}
                                        </span>
                                    </div>
                                    <div v-if="item.coa.length > 0" class="flex w-full flex-row justify-between">
                                        <blockquote class="mt-1 w-full border-l-2">
                                            <ul class="my-2 ml-2 w-full list-disc pr-2 [&>li]:mt-2">
                                                <li v-for="subItem in item.coa" class="flex w-full flex-row justify-between font-light">
                                                    <span> {{ subItem.code }} - {{ subItem.name }}</span>
                                                    <span>{{ formatCurrency(subItem.balance) }}</span>
                                                </li>
                                            </ul>
                                        </blockquote>
                                    </div>
                                </td>
                            </tr>

                            <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="w-full px-2 align-middle font-bold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                    <div class="flex flex-row justify-between">
                                        <span> TOTAL {{ assets.name.toUpperCase() }} </span>
                                        <span> {{ formatCurrency(assets.total_balance) }} </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="my-4 flex w-2/4 flex-col space-y-4">
                    <div>
                        <table class="w-full caption-bottom text-sm">
                            <thead class="border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th
                                        class="h-10 w-1/3 px-2 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5"
                                    >
                                        Liabilities
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="w-full [&_tr:last-child]:border-0">
                                <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="w-full px-2 align-middle font-bold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                        <div class="flex flex-row justify-between">
                                            <span>
                                                {{ liabilities.name.toUpperCase() }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>

                                <tr
                                    class="border-b font-medium transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted"
                                    v-for="item in liabilities.sub_category"
                                    :key="item.id"
                                >
                                    <td class="px-2 py-2 pl-5 align-middle font-semibold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                        <div class="flex flex-row justify-between">
                                            <span>
                                                {{ item.code }} -
                                                {{ item.name.toUpperCase() }}
                                            </span>
                                            <span>
                                                {{ formatCurrency(item.total_balance) }}
                                            </span>
                                        </div>
                                        <div v-if="item.coa.length > 0" class="flex w-full flex-row justify-between">
                                            <blockquote class="mt-1 w-full border-l-2">
                                                <ul class="my-2 ml-2 w-full list-disc pr-2 [&>li]:mt-2">
                                                    <li v-for="subItem in item.coa" class="flex w-full flex-row justify-between font-light">
                                                        <span> {{ subItem.code }} - {{ subItem.name }}</span>
                                                        <span>{{ formatCurrency(subItem.balance) }}</span>
                                                    </li>
                                                </ul>
                                            </blockquote>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="w-full px-2 align-middle font-bold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                        <div class="flex flex-row justify-between">
                                            <span> TOTAL {{ liabilities.name.toUpperCase() }} </span>
                                            <span> {{ formatCurrency(liabilities.total_balance) }} </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <table class="w-full caption-bottom text-sm">
                            <thead class="border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th
                                        class="h-10 w-1/3 px-2 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5"
                                    >
                                        Equity
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="w-full [&_tr:last-child]:border-0">
                                <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="w-full px-2 align-middle font-bold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                        <div class="flex flex-row justify-between">
                                            <span>
                                                {{ equity.name.toUpperCase() }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>

                                <tr
                                    class="border-b font-medium transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted"
                                    v-for="item in equity.sub_category"
                                    :key="item.id"
                                >
                                    <td class="px-2 py-2 pl-5 align-middle font-semibold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                        <div class="flex flex-row justify-between">
                                            <span>
                                                {{ item.code }} -
                                                {{ item.name.toUpperCase() }}
                                            </span>
                                            <span>
                                                {{ formatCurrency(item.total_balance) }}
                                            </span>
                                        </div>
                                        <div v-if="item.coa.length > 0" class="flex w-full flex-row justify-between">
                                            <blockquote class="mt-1 w-full border-l-2">
                                                <ul class="my-2 ml-2 w-full list-disc pr-2 [&>li]:mt-2">
                                                    <li v-for="subItem in item.coa" class="flex w-full flex-row justify-between font-light">
                                                        <span> {{ subItem.code }} - {{ subItem.name }}</span>
                                                        <span>{{ formatCurrency(subItem.balance) }}</span>
                                                    </li>
                                                </ul>
                                            </blockquote>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="w-full border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="w-full px-2 align-middle font-bold [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5">
                                        <div class="flex flex-row justify-between">
                                            <span> TOTAL {{ equity.name.toUpperCase() }} </span>
                                            <span> {{ formatCurrency(equity.total_balance) }} </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flex flex-row items-start space-x-5">
                <Table class="border bg-gray-500 dark:bg-gray-700">
                    <TableFooter>
                        <TableRow class="font-bold">
                            <TableCell class="w-2/3 text-right uppercase">TOTAL ASSETS</TableCell>
                            <TableCell class="text-right font-bold">
                                <span v-if="!onProses">
                                    {{ formatCurrency(assets.total_balance) }}
                                </span>
                                <span v-else> <Skeleton class="h-5 w-full" /> </span
                            ></TableCell>
                        </TableRow>
                    </TableFooter>
                </Table>

                <Table class="border bg-gray-500 dark:bg-gray-700">
                    <TableFooter>
                        <TableRow class="font-bold">
                            <TableCell class="w-2/3 text-right uppercase">TOTAL LIABILITEIS + EQUITY</TableCell>
                            <TableCell class="text-right font-bold">
                                <span v-if="!onProses">
                                    {{ formatCurrency(liabilities.total_balance + equity.total_balance) }}
                                </span>
                                <span v-else> <Skeleton class="h-5 w-full" /> </span
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useColorMode } from '@vueuse/core';
import { router, Link } from '@inertiajs/vue3';
import Label from '@/Components/ui/label/Label.vue';
import { formatCurrency } from '@/lib/utils';
import { Skeleton } from '@/Components/ui/skeleton';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow, TableFooter } from '@/Components/ui/table';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { AlertCircle } from 'lucide-vue-next';
import { ReloadIcon } from '@radix-icons/vue';
import Button from '@/Components/ui/button/Button.vue';
const mode = useColorMode();
const props = defineProps({
    accounts: Object,
    date: String,
});

const assets = computed(() => {
    return props.accounts[0];
});

const liabilities = computed(() => {
    return props.accounts[1];
});

const equity = computed(() => {
    return props.accounts[2];
});

const revenue = computed(() => {
    return props.accounts[3];
});

const expense = computed(() => {
    return props.accounts[4];
});

const sortedCoa = (coaList) => {
    return computed(() => {
        return [...coaList].sort((a, b) => a.code.localeCompare(b.code));
    });
};

const onProses = ref(false);
const today = new Date();
const startOfYear = new Date(today.getFullYear(), 0, 1); // 1 Januari tahun ini

const startDate = ref(startOfYear);
const endDate = ref(today);

const fetchData = () => {
    router.get(
        route('balance-sheet'),
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

const getReport = () => {
    router.get(route('report-balance-sheet'), {
        start_date: startDate.value,
        end_date: endDate.value,
    });
};
</script>
