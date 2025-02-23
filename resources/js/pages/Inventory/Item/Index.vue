<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Item / Product</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <div class="">
                <h1 class="mb-4 text-2xl font-bold">Items List</h1>

                <!-- Search & Filter -->
                <div class="mb-4 flex gap-4">
                    <Select v-model="limit">
                        <SelectTrigger class="w-36">{{ limit }}</SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in ['10', '20', '50', '100', '200']" :key="option" :value="option.toString()">
                                {{ option }}
                            </SelectItem>
                        </SelectContent>
                    </Select>

                    <Input v-model="search" placeholder="Search items..." class="w-1/3" />
                    <Select v-model="category">
                        <SelectTrigger class="w-1/3">Filter by Category</SelectTrigger>
                        <SelectContent>
                            <SelectItem value="0">All Categories</SelectItem>
                            <SelectItem v-for="cat in categories" :key="cat.id" :value="cat.id.toString()">
                                {{ cat.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>

                    <Select v-model="sortBy">
                        <SelectTrigger class="w-36">{{ sortByOption.find((x) => x.value == sortBy).label }}</SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in sortByOption" :key="option" :value="option.value.toString()">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>

                    <Button @click="router.get(route('item.create'))" class="ml-auto">+ Add Item</Button>
                </div>
                <WhenVisible :data="items.data">
                    <template #fallback>
                        <div class="my-6 w-full text-center">Please wait</div>
                    </template>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>#</TableHead>
                                <TableHead>SKU / Code</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Category</TableHead>
                                <TableHead>Stock</TableHead>
                                <TableHead>Cost Price</TableHead>
                                <TableHead>Selling Price</TableHead>
                                <TableHead>Created At</TableHead>
                                <TableHead>Actions</TableHead>
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
                                        <span>{{ item.minimum_stock }}</span>
                                        <span :class="item.stock < item.minimum_stock ? 'text-red-500' : 'text-green-500'">
                                            {{ item.stock < item.minimum_stock ? 'Low Stock' : 'Available' }}
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
                                            <DropdownMenuLabel>Actions</DropdownMenuLabel>
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
import Pagination from './Pagination.vue';
import { watchDebounced } from '@vueuse/core';
import { formatCurrency } from '@/lib/utils';
import { EllipsisVertical } from 'lucide-vue-next';

const props = defineProps({
    limit: Number,
    items: Object,
    categories: Array,
    filters: Object,
});

const sortByOption = [
    {
        label: 'Default',
        value: 'default',
    },
    {
        label: 'Low to High',
        value: 'price_low_high',
    },
    {
        label: 'High to Low',
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

const editData = ref(null);
const isEditOpen = ref(false);
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
            editData.value = item;
            isEditOpen.value = true;
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
            '/inventory/item',
            { search: search.value, category: category.value, limit: limit.value, sortBy: sortBy.value },
            { preserveState: true, replace: true },
        );
    },
    { debounce: 700, maxWait: 1000 },
);

// watch([search, category, limit], () => {
//     router.get('/inventory/item', { search: search.value, category: category.value, limit: limit.value }, { preserveState: true, replace: true });
// });
</script>
