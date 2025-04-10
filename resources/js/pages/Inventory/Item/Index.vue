<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">{{ $t('product.title') }}</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <div class="">
                <h1 class="mb-4 text-2xl font-bold">{{ t('product.items_list') }}</h1>

                <!-- Search & Filter -->
                <div class="mb-4 flex items-end gap-4">
                    <div class="grid w-36 max-w-sm items-center gap-1.5">
                        <Label for="email">{{ t('product.show') }}</Label>
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
                        <Label for="email">{{ t('product.search') }}</Label>
                        <Input v-model="search" :placeholder="t('product.search')" />
                    </div>
                    <div class="grid w-1/5 max-w-sm items-center gap-1.5">
                        <Label for="email">{{ t('product.category') }}</Label>

                        <Select v-model="category">
                            <SelectTrigger>{{ categories.find((x) => x.id == category)?.name ?? t('product.all_categories') }}</SelectTrigger>
                            <SelectContent>
                                <SelectItem value="0">{{ t('product.all_categories') }}</SelectItem>
                                <SelectItem v-for="cat in categories" :key="cat.id" :value="cat.id.toString()">
                                    {{ cat.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="grid w-1/5 max-w-sm items-center gap-1.5">
                        <Label for="email">{{ t('product.sort_by') }}</Label>
                        <Select v-model="sortBy">
                            <SelectTrigger>{{ sortByOption.find((x) => x.value == sortBy).label }}</SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="option in sortByOption" :key="option" :value="option.value.toString()">
                                    {{ option.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <Button @click="router.get(route('item.create'))" class="ml-auto">+ {{ t('product.add_item') }}</Button>
                </div>
                <WhenVisible :data="items.data">
                    <template #fallback>
                        <div class="my-6 w-full text-center">{{ t('product.please_wait') }}</div>
                    </template>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>#</TableHead>
                                <TableHead>{{ t('product.sku_code') }}</TableHead>
                                <TableHead>{{ t('product.name') }}</TableHead>
                                <TableHead>{{ t('product.category') }}</TableHead>
                                <TableHead>{{ t('product.stock') }}</TableHead>
                                <TableHead>{{ t('product.cost_price') }}</TableHead>
                                <TableHead>{{ t('product.selling_price') }}</TableHead>
                                <TableHead>{{ t('product.created_at') }}</TableHead>
                                <TableHead>{{ t('product.actions') }}</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, index) in items.data" :key="item.id">
                                <TableCell>{{ (items.current_page - 1) * items.per_page + index + 1 }}</TableCell>
                                <TableCell>{{ item.sku ?? '-' }}</TableCell>
                                <TableCell>{{ item.name }}</TableCell>
                                <TableCell>{{ item.category?.name || '-' }}</TableCell>
                                <TableCell>
                                    <div class="flex flex-col justify-center">
                                        <span>{{ item.last_balances?.last_balance }}</span>
                                        <span :class="item.last_balances?.last_balance < item.minimum_stock ? 'text-red-500' : 'text-green-500'">
                                            {{ item.last_balances?.last_balance < item.minimum_stock ? 'Low Stock' : 'Available' }}
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell>{{ formatCurrency(item.cost_price) }}</TableCell>
                                <TableCell>{{ formatCurrency(item.selling_price) }}</TableCell>
                                <TableCell>{{ item.created_at }}</TableCell>
                                <TableCell>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <EllipsisVertical class="cursor-pointer" :size="16" :stroke-width="0.5" />
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent class="w-24">
                                            <DropdownMenuLabel>{{ t('product.actions') }}</DropdownMenuLabel>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuGroup>
                                                <DropdownMenuItem
                                                    class="cursor-pointer"
                                                    v-for="(action, actionIndex) in actions"
                                                    :key="actionIndex"
                                                    @click="action.handler(item)"
                                                >
                                                    <span>{{ action.label }}</span>
                                                </DropdownMenuItem>
                                            </DropdownMenuGroup>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </WhenVisible>

                <!-- Pagination -->
                <Pagination :links="items.links" />
            </div>
        </div>
        <DeleteDialog v-model:open="isDeleteDialogOpen" v-model:id="deleteId" :route-link="'item.destroy'" @close="isDeleteDialogOpen = false" />
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { vAutoAnimate } from '@formkit/auto-animate/vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import DeleteDialog from '@/Components/DeleteDialog.vue';
import { usePage, router, WhenVisible } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectTrigger, SelectContent, SelectItem } from '@/components/ui/select';
import { Table, TableHead, TableRow, TableHeader, TableBody, TableCell } from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuPortal,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import Pagination from '@/Components/Pagination.vue';
import { watchDebounced } from '@vueuse/core';
import { formatCurrency } from '@/lib/utils';
import { EllipsisVertical } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    limit: Number,
    items: Object,
    categories: Array,
    units: Array,
    filters: Object,
});
const { t } = useI18n();
const sortByOption = [
    {
        label: 'Default',
        value: 'default',
    },
    {
        label: 'Low to High (Selling Price)',
        value: 'price_low_high',
    },
    {
        label: 'High to Low (Selling Price)',
        value: 'price_high_low',
    },
    {
        label: 'A-Z (Name)',
        value: 'name_az',
    },
    {
        label: 'Lowest Stock',
        value: 'lowest_stock',
    },
];

const deleteId = ref(null);
const isDeleteDialogOpen = ref(false);
const limit = ref(props.filters.limit?.toString() || '10');
const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');
const sortBy = ref(props.filters.sortBy || 'default');

const actions = ref([
    {
        label: 'Detail',
        handler: (item) => {
            router.get('/inventory/item/' + item.id);
        },
    },
    {
        label: 'Delete',
        handler: (item) => {
            deleteId.value = item.id;
            isDeleteDialogOpen.value = true;
        },
    },
]);

watchDebounced(
    [search, category, limit, sortBy],
    () => {
        router.get(
            route('item.index'),
            { search: search.value, category: category.value, limit: limit.value, sortBy: sortBy.value },
            { preserveState: true, preserveScroll: true },
        );
    },
    { debounce: 200, maxWait: 1000 },
);

// watch([search, category, limit], () => {
//     router.get('/inventory/item', { search: search.value, category: category.value, limit: limit.value }, { preserveState: true, replace: true });
// });
</script>
