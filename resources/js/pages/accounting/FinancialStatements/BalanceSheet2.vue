<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="font-semibold md:text-2xl">Balance Sheet</h1>
        </div>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
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
                <!-- Tabel Aset -->

                <div>
                    <h2 class="mb-2 font-semibold">Assets</h2>
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

                <Table class="border bg-gray-500 dark:bg-gray-700">
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
                    <h2 class="mb-2 font-semibold">Liabilities</h2>
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
                    <h2 class="mb-2 font-semibold">Equity</h2>
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
                <Table class="border bg-gray-500 dark:bg-gray-700">
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
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

import { ReloadIcon } from '@radix-icons/vue';
import Button from '@/components/ui/button/Button.vue';
const mode = useColorMode();
const props = defineProps({
    balanceSheet: Object,
    date: String,
});

const onProses = ref(false);
const today = new Date();
const startOfYear = new Date(today.getFullYear(), 0, 1); // 1 Januari tahun ini

const startDate = ref(startOfYear);
const endDate = ref(today);

const tahun = ref(new Date().getFullYear().toString());

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

const fetchData = () => {
    router.get(
        route('balance-sheet.index'),
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
