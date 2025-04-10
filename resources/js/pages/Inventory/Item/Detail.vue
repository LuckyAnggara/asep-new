<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg md:text-2xl">{{ $t('product.item_detail') }}</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center space-y-4 rounded-lg border border-dashed p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <Button @click="router.get(route('item.index'))" class=""> <ChevronLeft class="h-4 w-4" /> {{ $t('product.back') }}</Button>
                <div>
                    <DropdownMenu v-if="!isEdit">
                        <DropdownMenuTrigger as-child>
                            <Button class=""> <EllipsisVertical class="h-4 w-4" /> {{ $t('product.actions') }}</Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="w-24">
                            <DropdownMenuLabel>{{ $t('product.actions') }}</DropdownMenuLabel>
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

                    <div v-else class="flex flex-row space-x-2">
                        <Button class="w-24" @click="toggleEdit" variant="outline">{{ $t('product.cancel') }}</Button>
                        <Button class="w-24" @click="isConfirmDialog = true"><Save class="h-4 w-4" /> {{ $t('product.save') }}</Button>
                    </div>
                </div>
            </div>
            <Alert variant="warning" v-if="isEdit">
                <AlertCircle class="h-4 w-4" />
                <AlertTitle>{{ $t('product.edit_mode') }}</AlertTitle>
            </Alert>
            <Tabs default-value="detail" class="mb-5">
                <TabsList>
                    <TabsTrigger value="detail">Detail</TabsTrigger>
                    <TabsTrigger value="mutasi">Mutasi</TabsTrigger>
                    <TabsTrigger value="preferences">Performance</TabsTrigger>
                </TabsList>

                <TabsContent value="detail" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">Detail</h3>
                    <div class="flex w-full flex-row space-x-8">
                        <!-- Image Section -->
                        <div class="flex w-1/3 flex-col items-center">
                            <img
                                v-if="!isEdit"
                                :src="item.image ? '/storage/' + item.image : '/storage/item/default-item.png'"
                                alt="Item Image"
                                class="object-fit h-80 w-full rounded-lg shadow-md"
                            />

                            <div v-else class="flex flex-col items-center gap-3">
                                <img v-if="previewImage" :src="previewImage" class="object-fit h-80 w-full rounded-lg shadow-md" />
                                <input type="file" @change="handleImageUpload" class="hidden" ref="imageInput" />
                                <div class="flex flex-row space-x-4">
                                    <Button @click="removeImage" class="w-24" variant="outline"><X class="h-4 w-4" /> {{ $t('product.image.remove') }} </Button>
                                    <Button @click="resetImage" class="w-24" variant="outline"
                                        ><ResetIcon class="h-4 w-4" /> {{ $t('product.image.reset') }}
                                    </Button>
                                    <Button @click="open" class="w-24"><ArrowLeftRight class="h-4 w-4" />{{ $t('product.image.change') }}</Button>
                                </div>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="w-2/3 space-y-2">
                            <div class="flex flex-row items-center space-x-2">
                                <Label for="item_category_id" class="w-1/5">{{ $t('product.name') }}</Label>
                                <span v-if="!isEdit">{{ form.name }}</span>
                                <div v-else class="w-4/5">
                                    <Input v-model="form.name" class="w-full rounded-md border p-2" :placeholder="$t('product.item_name')" />
                                </div>
                                <span
                                    v-if="!isEdit"
                                    class="rounded-md px-3 py-1 text-sm"
                                    :class="form.minimum_stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                >
                                    {{ form.minimum_stock > 0 ? $t('product.in_stock') : $t('product.out_of_stock') }}
                                </span>
                            </div>

                            <div class="flex flex-row items-center space-x-2">
                                <Label for="item_category_id" class="w-1/5">{{ $t('product.sku_code') }}</Label>
                                <span v-if="!isEdit">{{ form.sku }}</span>
                                <div v-else class="w-4/5">
                                    <Input v-model="form.sku" class="w-full rounded-md border p-2" placeholder="Stock Keeping Unit (SKU) / Code" />
                                </div>
                            </div>

                            <div class="flex flex-row items-center space-x-2">
                                <Label for="item_category_id" class="w-1/5">{{ $t('product.category') }}</Label>
                                <span v-if="!isEdit">{{ item.category?.name || 'N/A' }}</span>
                                <div v-else class="w-4/5">
                                    <Select v-model="form.item_category_id">
                                        <SelectTrigger>
                                            <SelectValue :placeholder="$t('product.select_category')" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="category in categories" :key="category.id" :value="category.id.toString()">
                                                {{ category.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <div class="flex flex-row items-center space-x-2">
                                <Label class="w-1/5">{{ $t('product.unit') }}</Label>
                                <span v-if="!isEdit">{{ item.unit?.name || 'N/A' }}</span>
                                <div v-else class="w-4/5">
                                    <Select v-model="form.unit_id">
                                        <SelectTrigger>
                                            <SelectValue :placeholder="$t('product.select_unit')" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="unit in units" :key="unit.id" :value="unit.id.toString()">
                                                {{ unit.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <div class="flex flex-row items-center space-x-2">
                                <Label class="w-1/5">{{ $t('product.cost_price') }}</Label>
                                <span v-if="!isEdit">{{ formatCurrency(form.cost_price) }}</span>
                                <div v-else class="w-4/5">
                                    <NumberField id="cost_price" v-model="form.cost_price" :format-options="formatOptions">
                                        <NumberFieldContent>
                                            <NumberFieldDecrement />
                                            <NumberFieldInput />
                                            <NumberFieldIncrement />
                                        </NumberFieldContent>
                                    </NumberField>
                                </div>
                            </div>

                            <div class="flex flex-row items-center space-x-2">
                                <Label class="w-1/5">{{ $t('product.selling_price') }}</Label>
                                <span v-if="!isEdit">{{ formatCurrency(form.selling_price) }}</span>
                                <div v-else class="w-4/5">
                                    <NumberField id="selling_price" v-model="form.selling_price" :format-options="formatOptions">
                                        <NumberFieldContent>
                                            <NumberFieldDecrement />
                                            <NumberFieldInput />
                                            <NumberFieldIncrement />
                                        </NumberFieldContent>
                                    </NumberField>
                                </div>
                            </div>
                            <div class="flex flex-row items-center space-x-2">
                                <Label class="w-1/5">{{ $t('product.minimum_stock') }}</Label>
                                <span v-if="!isEdit">{{ form.minimum_stock }}</span>
                                <div v-else class="w-4/5">
                                    <NumberField id="minimum_stock" v-model="form.minimum_stock" :min="1" required>
                                        <NumberFieldContent>
                                            <NumberFieldDecrement />
                                            <NumberFieldInput />
                                            <NumberFieldIncrement />
                                        </NumberFieldContent>
                                    </NumberField>
                                </div>
                            </div>
                            <div class="flex flex-row items-center space-x-2">
                                <Label class="w-1/5">{{ $t('product.last_balance') }}</Label>
                                <span>{{ item.last_balances.last_balance }}</span>
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="mutasi" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">Balance</h3>

                    <div class="flex w-full flex-row space-x-8">
                        <!-- Details Section -->
                        <div class="w-2/3 space-y-2">
                            <Table>
                                <TableHeader>
                                    <TableRow class="font-bold uppercase">
                                        <TableHead>#</TableHead>
                                        <TableHead>Tanggal</TableHead>
                                        <TableHead>Jenis</TableHead>
                                        <TableHead>Quantity</TableHead>
                                        <TableHead>Balance</TableHead>
                                        <!-- <TableHead></TableHead> -->
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="value in transactionsWithBalance" :key="value.id">
                                        <TableHead>{{ value.id }}</TableHead>
                                        <TableCell>{{ value.transaction_date }}</TableCell>
                                        <TableCell>
                                            <span :class="value.type === 'in' ? 'text-green-500' : 'text-red-500'">
                                                {{ value.type.toUpperCase() }}
                                            </span></TableCell
                                        >
                                        <TableCell>{{ value.quantity }}</TableCell>
                                        <TableCell>{{ value.balance }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <DeleteDialog v-model:open="isDeleteDialogOpen" v-model:id="item.id" :route-link="'item.destroy'" @close="isDeleteDialogOpen = false" />
        <UpdateDialog v-model:open="isConfirmDialog" v-model:id="item.id" :route-link="'item.update'" @close="updateDone" :data="form" />
    </AuthenticatedLayout>
</template>

<script setup>
import { usePage, Link, router, useForm } from '@inertiajs/vue3';
import DeleteDialog from '@/Components/DeleteDialog.vue';
import UpdateDialog from '@/Components/UpdateDialog.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/Components/ui/input';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ChevronLeft, EllipsisVertical, AlertCircle, Save, X, ArrowLeftRight } from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { NumberField, NumberFieldContent, NumberFieldDecrement, NumberFieldIncrement, NumberFieldInput } from '@/components/ui/number-field';
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
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/components/ui/select';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/Components/ui/tabs';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Label } from '@/components/ui/label';
import { computed, ref, useTemplateRef } from 'vue';
import { formatCurrency } from '@/lib/utils';
import { reactive } from 'vue';

import { useFileDialog } from '@vueuse/core';
import { ResetIcon } from '@radix-icons/vue';

const { files, open, reset, onCancel, onChange } = useFileDialog({
    accept: 'image/*', // Set to accept only image files
});

onChange((files) => {
    const file = files[0];
    if (file) {
        form.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
});

const resetImage = () => {
    form.image = null;
    previewImage.value = props.item.image ? '/storage/' + props.item.image : '/storage/item/default-item.png';
};

const removeImage = () => {
    form.image = null;
    previewImage.value = null;
};

onCancel(() => {
    /** do something on cancel */
});
const page = usePage();

const company = computed(() => page.props.auth.company);

const props = defineProps({
    item: Object,
    categories: Array,
    units: Array,
    transactions: Array, // Data transaksi yang diterima sebagai props
});

const transactionsWithBalance = computed(() => {
    let balance = 0;

    // Urutkan berdasarkan tanggal transaksi secara DESCENDING
    // Jika tanggal sama, urutkan berdasarkan ID secara DESCENDING
    const sortedTransactions = [...props.transactions].sort((a, b) => {
        if (new Date(a.transaction_date) !== new Date(b.transaction_date)) {
            return new Date(b.transaction_date) - new Date(a.transaction_date);
        }
        return b.id - a.id; // Jika tanggal sama, ID terbesar dulu
    });

    // Hitung balance dari transaksi terakhir ke awal (karena urutan sudah descending)
    const transactionsWithBalance = [...sortedTransactions]
        .reverse() // Balik dulu supaya balance dihitung dari transaksi awal
        .map((trx) => {
            balance += trx.type === 'in' ? trx.quantity : -trx.quantity;
            return { ...trx, balance };
        })
        .reverse(); // Balik lagi ke urutan descending

    return transactionsWithBalance;
});

const isDeleteDialogOpen = ref(false);
const isConfirmDialog = ref(false);
const isEdit = ref(false);
const previewImage = ref(props.item.image ? '/storage/' + props.item.image : '/storage/item/default-item.png');

const formatOptions = {
    style: 'currency',
    currency: company.value.currency,
    minimumFractionDigits: company.value.decimal,
    currencyDisplay: 'code',
    currencySign: 'accounting',
};

const form = useForm({
    id: props.item.id,
    name: props.item.name,
    image: props.item.image,
    minimum_stock: props.item.minimum_stock,
    cost_price: props.item.cost_price,
    selling_price: props.item.selling_price,
    item_category_id: props.item.item_category_id.toString() || null,
    unit_id: props.item.unit_id.toString() || null,
    sku: props.item.sku,
    _method: 'put',
});

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const updateDone = () => {
    isConfirmDialog.value = false;
    isEdit.value = false;
};

const actions = ref([
    {
        label: 'Edit',
        handler: (item) => {
            isEdit.value = true;
        },
    },
    {
        label: 'Delete',
        handler: () => {
            isDeleteDialogOpen.value = true;
        },
    },
]);

function toggleEdit() {
    form.reset();
    resetImage();
    isEdit.value = !isEdit.value;
}
</script>
