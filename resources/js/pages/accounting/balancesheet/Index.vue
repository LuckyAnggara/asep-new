<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Journal Entry</h1>
        </div>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <!-- Filter Tanggal -->
            <div class="grid w-fit gap-2">
                <Label for="reference">Data s.d Tanggal</Label>
                <VueDatePicker
                    :preview-format="'dd/MMM/yyyy'"
                    :format="'dd MMMM yyyy'"
                    auto-apply
                    v-model="date"
                    @update:model-value="fetchBalanceSheet"
                    :enable-time-picker="false"
                    :dark="mode == 'dark'"
                ></VueDatePicker>
            </div>

            <div class="my-4 flex flex-col space-y-4 xl:w-2/3">
                <!-- Tabel Aset -->

                <div>
                    <h2 class="mb-2 text-xl font-semibold">Assets</h2>
                    <Table class="text-md border">
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
                                v-for="asset in balanceSheet.assets"
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
                                    >Total Assets</TableCell
                                >
                                <TableCell class="text-right font-bold">
                                    <span v-if="!onProses">
                                        {{ formatCurrency(totalAssets) }}
                                    </span>
                                    <span v-else>
                                        <Skeleton class="h-5 w-full" />
                                    </span>
                                </TableCell>
                            </TableRow>
                        </TableFooter>
                    </Table>
                </div>

                <Table class="border bg-gray-500 text-lg dark:bg-gray-700">
                    <TableFooter>
                        <TableRow class="font-bold">
                            <TableCell class="w-2/3 text-right uppercase"
                                >Total Assets</TableCell
                            >
                            <TableCell class="text-right font-bold">
                                <span v-if="!onProses">
                                    {{ formatCurrency(totalAssets) }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" /> </span
                            ></TableCell>
                        </TableRow>
                    </TableFooter>
                </Table>

                <!-- Tabel Liabilities -->

                <div>
                    <h2 class="mb-2 text-xl font-semibold">Liabilities</h2>
                    <Table class="text-md border">
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
                                v-for="asset in balanceSheet.liabilities"
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
                                        {{ formatCurrency(totalLiabilities) }}
                                    </span>
                                    <span v-else>
                                        <Skeleton class="h-5 w-full" />
                                    </span>
                                </TableCell>
                            </TableRow>
                        </TableFooter>
                    </Table>
                </div>

                <!-- Tabel Equity -->

                <div>
                    <h2 class="mb-2 text-xl font-semibold">Equity</h2>
                    <Table class="text-md border">
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
                                v-for="asset in balanceSheet.equity"
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
                                    >Total Equity</TableCell
                                >
                                <TableCell class="text-right font-bold">
                                    <span v-if="!onProses">
                                        {{ formatCurrency(totalEquity) }}
                                    </span>
                                    <span v-else>
                                        <Skeleton class="h-5 w-full" /> </span
                                ></TableCell>
                            </TableRow>
                        </TableFooter>
                    </Table>
                </div>
                <Table class="border bg-gray-500 text-lg dark:bg-gray-700">
                    <TableFooter>
                        <TableRow class="font-bold">
                            <TableCell class="w-2/3 text-right uppercase"
                                >Total Liabilities and Equity</TableCell
                            >
                            <TableCell class="text-right font-bold">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            totalLiabilities + totalEquity,
                                        )
                                    }}
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
    balanceSheet: Object,
    date: String,
});

const onProses = ref(false);
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
