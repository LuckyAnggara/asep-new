<script setup>
import { usePage } from '@inertiajs/vue3';
import {
    Table,
    TableHead,
    TableHeader,
    TableRow,
    TableBody,
    TableCell,
} from '@/components/ui/table';

const { balanceSheet } = usePage().props;

// Fungsi format mata uang
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};
</script>

<template>
    <div class="mx-auto max-w-6xl rounded-md bg-white p-6 shadow-md">
        <h2 class="mb-4 text-center text-2xl font-bold">Neraca Keuangan</h2>

        <div class="grid grid-cols-2 gap-6">
            <!-- AKTIVA -->
            <div>
                <h3 class="mb-2 text-xl font-semibold">AKTIVA</h3>
                <Table class="w-full border">
                    <TableHeader>
                        <TableRow class="bg-gray-100">
                            <TableHead class="p-4 text-left">Akun</TableHead>
                            <TableHead class="p-4 text-right">Jumlah</TableHead>
                            <TableHead class="p-4 text-right">%</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template
                            v-for="group in balanceSheet.aktiva"
                            :key="group.name"
                        >
                            <!-- Header Kategori -->
                            <TableRow class="bg-gray-200">
                                <TableCell class="p-4 font-bold" colspan="3">{{
                                    group.name
                                }}</TableCell>
                            </TableRow>
                            <!-- Detail Akun -->
                            <template
                                v-for="item in group.items"
                                :key="item.name"
                            >
                                <TableRow class="border-b">
                                    <TableCell class="p-4 pl-6">{{
                                        item.name
                                    }}</TableCell>
                                    <TableCell class="text-right">{{
                                        formatCurrency(item.amount)
                                    }}</TableCell>
                                    <TableCell class="text-right"
                                        >{{ item.percentage }}%</TableCell
                                    >
                                </TableRow>
                            </template>
                            <!-- Total -->
                            <TableRow class="bg-gray-300 font-bold">
                                <TableCell class="p-4"
                                    >TOTAL {{ group.name }}</TableCell
                                >
                                <TableCell class="text-right">{{
                                    formatCurrency(group.total)
                                }}</TableCell>
                                <TableCell class="text-right"
                                    >{{ group.percentage }}%</TableCell
                                >
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>

            <!-- PASIVA -->
            <div>
                <h3 class="mb-2 text-xl font-semibold">PASIVA</h3>
                <Table class="w-full border">
                    <TableHeader>
                        <TableRow class="bg-gray-100">
                            <TableHead class="p-4 text-left">Akun</TableHead>
                            <TableHead class="p-4 text-right">Jumlah</TableHead>
                            <TableHead class="p-4 text-right">%</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template
                            v-for="group in balanceSheet.pasiva"
                            :key="group.name"
                        >
                            <!-- Header Kategori -->
                            <TableRow class="bg-gray-200">
                                <TableCell class="p-4 font-bold" colspan="3">{{
                                    group.name
                                }}</TableCell>
                            </TableRow>
                            <!-- Detail Akun -->
                            <template
                                v-for="item in group.items"
                                :key="item.name"
                            >
                                <TableRow class="border-b">
                                    <TableCell class="p-4 pl-6">{{
                                        item.name
                                    }}</TableCell>
                                    <TableCell class="text-right">{{
                                        formatCurrency(item.amount)
                                    }}</TableCell>
                                    <TableCell class="text-right"
                                        >{{ item.percentage }}%</TableCell
                                    >
                                </TableRow>
                            </template>
                            <!-- Total -->
                            <TableRow class="bg-gray-300 font-bold">
                                <TableCell class="p-4"
                                    >TOTAL {{ group.name }}</TableCell
                                >
                                <TableCell class="text-right">{{
                                    formatCurrency(group.total)
                                }}</TableCell>
                                <TableCell class="text-right"
                                    >{{ group.percentage }}%</TableCell
                                >
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
        </div>
    </div>
</template>
