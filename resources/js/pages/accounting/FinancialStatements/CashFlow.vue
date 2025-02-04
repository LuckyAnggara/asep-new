<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import { useColorMode } from '@vueuse/core';
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
    cash_flow: Object,
});
const today = new Date();
const startOfYear = new Date(today.getFullYear(), 0, 1); // 1 Januari tahun ini

const startDate = ref(startOfYear);
const endDate = ref(today);
const onProses = ref(false);

// Hitung net per kategori
const netOperating = computed(
    () =>
        props.cash_flow.operating.cash_in - props.cash_flow.operating.cash_out,
);

const netFinancing = computed(
    () =>
        props.cash_flow.financing.cash_in - props.cash_flow.financing.cash_out,
);

const netInvesting = computed(
    () =>
        props.cash_flow.investing.cash_in - props.cash_flow.investing.cash_out,
);

// Total Net Cash Flow
const netCashFlow = computed(
    () => netOperating.value + netFinancing.value + netInvesting.value,
);

const fetchData = () => {
    router.get(
        route('cash-flow.index'),
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
            <div class="mb-4 flex gap-4">
                <!-- Filter Tanggal -->
                <div class="grid w-fit gap-2">
                    <Label for="reference">Tanggal Data</Label>
                    <div
                        class="flex flex-row items-center space-x-4 text-center"
                    >
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
                            @click="fetchData"
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
            </div>

            <div v-if="cash_flow">
                <h2 class="mb-2 text-xl font-semibold">Des</h2>
                <Table class="text-md border">
                    <TableHeader class="">
                        <TableRow
                            class="text-center font-bold uppercase text-black"
                        >
                            <TableHead class="w-1/4">Deskripsi</TableHead>
                            <TableHead class="w-1/4 text-right"
                                >Kas Masuk</TableHead
                            >
                            <TableHead class="w-1/4 text-right"
                                >Kas Keluar</TableHead
                            >
                            <TableHead class="w-1/4 text-right"
                                >Net Cash Flow</TableHead
                            >
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow>
                            <TableCell> Operasional </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.operating.cash_in,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.operating.cash_out,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.operating.cash_in -
                                                cash_flow.operating.cash_out,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                        </TableRow>
                        <TableRow>
                            <TableCell> Investasi </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.investing.cash_in,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.investing.cash_out,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.investing.cash_in -
                                                cash_flow.investing.cash_out,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                        </TableRow>
                        <TableRow>
                            <TableCell> Pendanaan </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.financing.cash_in,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.financing.cash_out,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell class="border text-right">
                                <span v-if="!onProses">
                                    {{
                                        formatCurrency(
                                            cash_flow.financing.cash_in -
                                                cash_flow.financing.cash_out,
                                        )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                    <TableFooter>
                        <TableRow class="font-bold">
                            <TableCell class="text-right" colspan="3"
                                >Net Cash Flow</TableCell
                            >
                            <TableCell class="text-right font-bold">
                                <span v-if="!onProses">
                                    {{ formatCurrency(netCashFlow) }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                        </TableRow>
                    </TableFooter>
                </Table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
