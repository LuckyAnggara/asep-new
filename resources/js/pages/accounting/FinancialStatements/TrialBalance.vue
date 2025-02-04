<script setup>
import { computed, defineProps } from 'vue';
import {
    Table,
    TableHeader,
    TableRow,
    TableHead,
    TableBody,
    TableCell,
    TableFooter,
} from '@/Components/ui/table';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/ui/button/Button.vue';
import Label from '@/Components/ui/label/Label.vue';
import { Skeleton } from '@/Components/ui/skeleton';
import { useColorMode } from '@vueuse/core';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { ReloadIcon } from '@radix-icons/vue';
import { formatCurrency } from '@/lib/utils';
const props = defineProps({
    trialBalance: Array,
});
const today = new Date();
const startOfYear = new Date(today.getFullYear(), 0, 1); // 1 Januari tahun ini

const startDate = ref(startOfYear);
const endDate = ref(today);
const onProses = ref(false);
const mode = useColorMode();

const totalDebit = computed(() => {
    return props.trialBalance.reduce(
        (sum, entry) => sum + parseFloat(entry.debit || 0),
        0,
    );
});

const totalCredit = computed(() => {
    return props.trialBalance.reduce(
        (sum, entry) => sum + parseFloat(entry.credit || 0),
        0,
    );
});

console.info(props.trialBalance);

const fetchData = () => {
    router.get(
        route('trial-balance'),
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
            <h1 class="text-lg font-semibold md:text-2xl">Trial Balance</h1>
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
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Kode Akun</TableHead>
                            <TableHead>Nama Akun</TableHead>
                            <TableHead class="text-right">Saldo Awal</TableHead>
                            <TableHead class="text-right">Debit</TableHead>
                            <TableHead class="text-right">Kredit</TableHead>
                            <TableHead class="text-right"
                                >Saldo Akhir</TableHead
                            >
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow
                            v-for="(account, index) in props.trialBalance"
                            :key="index"
                        >
                            <TableCell>
                                <span v-if="!onProses">
                                    {{ account.account_code }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell>
                                <span v-if="!onProses">
                                    {{ account.account_name }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" /> </span
                            ></TableCell>
                            <TableCell class="text-right">
                                <span v-if="!onProses">
                                    {{
                                        account.opening_balance == 0
                                            ? '-'
                                            : formatCurrency(
                                                  account.opening_balance,
                                              )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" /> </span
                            ></TableCell>
                            <TableCell class="text-right">
                                <span v-if="!onProses">
                                    {{
                                        account.debit == 0
                                            ? '-'
                                            : formatCurrency(account.debit)
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" /> </span
                            ></TableCell>
                            <TableCell class="text-right">
                                <span v-if="!onProses">
                                    {{
                                        account.credit == 0
                                            ? '-'
                                            : formatCurrency(account.credit)
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" /> </span
                            ></TableCell>
                            <TableCell class="text-right">
                                <span v-if="!onProses">
                                    {{
                                        account.closing_balance == 0
                                            ? '-'
                                            : formatCurrency(
                                                  account.closing_balance,
                                              )
                                    }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" /> </span
                            ></TableCell>
                        </TableRow>
                    </TableBody>

                    <TableFooter>
                        <TableRow class="font-bold">
                            <TableCell class="text-right" colspan="3"
                                >Total</TableCell
                            >
                            <TableCell class="text-right font-bold">
                                <span v-if="!onProses">
                                    {{ formatCurrency(totalDebit) }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell class="text-right font-bold">
                                <span v-if="!onProses">
                                    {{ formatCurrency(totalCredit) }}
                                </span>
                                <span v-else>
                                    <Skeleton class="h-5 w-full" />
                                </span>
                            </TableCell>
                            <TableCell class="text-right font-bold">
                            </TableCell>
                        </TableRow>
                    </TableFooter>
                </Table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
