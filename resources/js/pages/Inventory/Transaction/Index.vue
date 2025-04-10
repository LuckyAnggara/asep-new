<script setup>
import { ref } from 'vue';
import { usePage, router, WhenVisible } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Select, SelectTrigger, SelectContent, SelectItem } from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    transactions: Object,
    filters: Object,
});

const limit = ref(props.filters?.limit || '10');
const sortBy = ref(props.filters?.sortBy || 'created_at');
const search = ref(props.filters?.search || '');

const searchTransactions = () => {
    router.get(route('inventory-transactions.index'), { search: search.value }, { preserveState: true });
};

const exportExcel = () => {
    window.location.href = route('inventory-transactions.export');
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">{{ $t('product.transaction.title') }}</h1>
        </div>
        <div class="flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <div class="">
                <div class="mb-4 flex justify-between">
                    <Button @click="exportExcel">Export to Excel</Button>
                </div>

                <div class="mb-4 flex items-end gap-4">
                    <div class="grid w-36 max-w-sm items-center gap-1.5">
                        <Label for="email">{{ $t('product.show') }}</Label>
                        <Select v-model="limit">
                            <SelectTrigger>{{ limit }}</SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="option in ['10', '20', '50', '100', '200']" :key="option" :value="option.toString()">
                                    {{ option }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid w-3/5 max-w-sm items-center gap-1.5">
                        <Label for="email">{{ $t('product.search') }}</Label>
                        <Input v-model="search" :placeholder="$t('product.search')" />
                    </div>

                    <Button @click="router.get(route('transaction.create'))" class="ml-auto">+ {{ $t('product.transaction.add_transaction') }}</Button>
                </div>

                <div class="mb-4 flex">
                    <Input v-model="search" placeholder="Search transactions..." class="mr-2" />
                    <Button @click="searchTransactions">Search</Button>
                </div>
                <WhenVisible data="permissions">
                    <template #fallback>
                        <div class="my-6 w-full text-center">{{ $t('member.please_wait') }}</div>
                    </template>
                    <div class="flex flex-col justify-between space-y-6">
                        <div class="scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 overflow-auto">
                            <Table>
                                <TableHeader>
                                    <TableRow class="font-bold uppercase">
                                        <TableHead> # </TableHead>
                                        <TableHead>Tanggal</TableHead>
                                        <TableHead>SKU</TableHead>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Jenis</TableHead>
                                        <TableHead>Quantity</TableHead>
                                        <!-- <TableHead>Balance</TableHead> -->
                                        <TableHead></TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="(item, index) in transactions.data" :key="item.id">
                                        <TableCell class="font-medium">
                                            {{ transactions.from + index }}
                                        </TableCell>
                                        <TableCell>{{ item.transaction_date }}</TableCell>
                                        <TableCell>{{ item.item.sku }}</TableCell>
                                        <TableCell>{{ item.item.name }}</TableCell>
                                        <TableCell>
                                            <span :class="item.type === 'in' ? 'text-green-500' : 'text-red-500'">
                                                {{ item.type.toUpperCase() }}
                                            </span></TableCell
                                        >
                                        <TableCell>{{ item.quantity }}</TableCell>
                                        <!-- <TableCell> ??? </TableCell> -->
                                        <TableCell></TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>
                </WhenVisible>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
