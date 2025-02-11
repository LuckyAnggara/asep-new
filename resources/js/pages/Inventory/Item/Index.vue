<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import { Input } from '@/Components/ui/input';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuGroup,
    DropdownMenuItem,
} from '@/components/ui/dropdown-menu';
import { EllipsisVertical, ListFilter, Search } from 'lucide-vue-next';
import { formatCurrency } from '@/lib/utils';

const props = defineProps({
    items: Array,
    categories: Array,
});

// 🏷️ Form Data for Adding New Item
const form = useForm({
    sku: '',
    name: '',
    barcode: '',
    category_id: '',
    image: null,
    cogs: '',
    price: '',
    min_stock: '',
    weight: '',
    description: '',
});

// 🔍 Search Feature
const searchQuery = ref('');
const filteredItems = computed(() => {
    return props.items.filter(
        (item) => item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || item.sku.toLowerCase().includes(searchQuery.value.toLowerCase()),
    );
});
const getItemPic = (item) => `/storage/${item}`;
// 🆕 Submit New Item
const submit = () => {
    form.post(route('items.store'));
};

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
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Items / Products</h1>
        </div>
        <div class="flex-1 flex-col rounded-lg border border-dashed p-6 shadow-sm">
            <div class="mx-auto p-6">
                <!-- 🔍 Search Input -->

                <div class="mb-4 flex flex-row items-end space-x-6">
                    <Button @click="toCreate()">Tambah Data</Button>
                    <div class="relative grid w-full items-center gap-1.5">
                        <Label>Search</Label>
                        <div class="relative w-full">
                            <Search class="absolute left-2 top-2.5 size-4 text-muted-foreground" />
                            <Input v-model="search" placeholder="Search" class="pl-8" />
                        </div>
                    </div>
                    <div class="w-18 relative grid items-center gap-1.5">
                        <Label>Filter</Label>
                        <Button type="button" @click="isSheetFilterOpen = !isSheetFilterOpen" variant="outline" size="icon">
                            <ListFilter :stroke-width="1.25" />
                        </Button>
                    </div>
                </div>
                <WhenVisible :data="accounts">
                    <template #fallback>
                        <div class="my-6 w-full text-center">Please wait</div>
                    </template>
                    <Table class="rounded-lg border shadow-sm">
                        <TableHeader>
                            <TableRow class="bg-gray-100">
                                <TableHead class="w-20 text-center">Image</TableHead>
                                <TableHead>SKU</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Unit</TableHead>
                                <TableHead>Category</TableHead>
                                <TableHead>Cogs</TableHead>
                                <TableHead>Price</TableHead>
                                <TableHead>Weight (kg)</TableHead>
                                <TableHead>Min Stock</TableHead>
                                <TableHead></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50">
                                <!-- 🖼 Image -->
                                <TableCell class="text-center">
                                    <img v-if="item.image" :src="`/storage/${item.image}`" class="h-12 w-12 rounded-md border object-cover" />
                                    <span v-else class="text-gray-400">No Image</span>
                                </TableCell>
                                <!-- 📛 Item Data -->
                                <TableCell>{{ item.sku }}</TableCell>
                                <TableCell>{{ item.name }}</TableCell>
                                <TableCell>{{ item.unit }}</TableCell>
                                <TableCell>{{ item.category?.name || 'Uncategorized' }}</TableCell>
                                <TableCell>{{ formatCurrency(item.cogs) }}</TableCell>
                                <TableCell>{{ formatCurrency(item.price) }}</TableCell>
                                <TableCell>{{ item.weight || 'N/A' }}</TableCell>
                                <TableCell>{{ item.min_stock }}</TableCell>
                                <!-- ✏️ Actions -->
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

                <!-- 📊 Items Table -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
