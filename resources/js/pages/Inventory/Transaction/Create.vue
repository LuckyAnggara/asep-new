<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">New Inventory Transaction</h1>
        </div>
        <div class="flex flex-col items-start justify-start space-y-2 rounded-lg border border-dashed p-6 shadow-sm">
            <form class="flex w-full flex-row items-start space-x-4">
                <div class="w-1/2 space-y-2">
                    <!-- Date -->
                    <div class="grid gap-2">
                        <Label for="date">Tanggal</Label>
                        <VueDatePicker
                            :preview-format="'dd/MMM/yyyy'"
                            :format="'dd MMMM yyyy'"
                            auto-apply
                            :enable-time-picker="false"
                            v-model="form.transaction_date"
                            :dark="mode == 'dark'"
                        ></VueDatePicker>
                    </div>
                    <!-- Reference -->
                    <div class="grid gap-2">
                        <Label for="reference">Nomor Referensi</Label>
                        <Input id="reference" v-model="form.reference" type="text" placeholder="Referensi akan dibuat otomatis (Optional)" />
                    </div>
                </div>

                <!-- Description -->
                <div class="flex w-1/2 flex-col items-end space-y-4">
                    <div class="grid w-full gap-2">
                        <Label for="description">Deskripsi</Label>
                        <Textarea v-model="form.description" class="resize-none" placeholder="Masukkan Deskripsi (Optional)" />
                    </div>

                    <!-- Submit Button -->
                    <Button type="button" @click="openAlertDialog = true" :disabled="onProses || form.transactions.length == 0">
                        <span v-if="onProses" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>

            <Separator />

            <div class="flex w-full flex-col space-y-4">
                <div class="flex flex-col space-y-2">
                    <Button class="w-fit" type="button" @click="addDetail">Tambah</Button>
                    <Table>
                        <TableHeader>
                            <TableRow class="font-bold uppercase">
                                <TableHead class="w-3/12">Item Name</TableHead>
                                <TableHead class="w-2/12">Movement Type</TableHead>
                                <TableHead class="w-2/12">Quantity</TableHead>
                                <TableHead class="w-1/12"></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, i) in form.transactions" :key="item.account_id">
                                <TableCell>
                                    <v-select
                                        v-model="item.item_id"
                                        append-to-body
                                        label="name"
                                        :reduce="(item) => item.id"
                                        :options="items"
                                        :loading="form.processing"
                                        @search="searchItems"
                                        :filterable="false"
                                        placeholder="Search items..."
                                        class="style-chooser z-50"
                                    >
                                        <template #no-options>
                                            <span class="text-gray-500">No items found...</span>
                                        </template>
                                    </v-select>
                                </TableCell>
                                <TableCell>
                                    <Select v-model="item.type">
                                        <SelectTrigger>{{ item.type.toUpperCase() }}</SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="in"> IN </SelectItem>
                                            <SelectItem value="out"> OUT </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </TableCell>
                                <TableCell>
                                    <NumberField v-model="item.quantity" :min="1" :default-value="1">
                                        <NumberFieldContent>
                                            <NumberFieldDecrement />
                                            <NumberFieldInput />
                                            <NumberFieldIncrement />
                                        </NumberFieldContent>
                                    </NumberField>
                                </TableCell>

                                <TableCell>
                                    <Button @click="removeDetail(i)" variant="ghost" size="icon">
                                        <Trash2 class="h-6 w-6" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
        <AlertDialog v-model:open="openAlertDialog">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Confirmation</AlertDialogTitle>
                    <AlertDialogDescription> Data Anda akan dikirim. Periksa kembali apakah semuanya sudah sesuai. Lanjutkan? </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <Button @click="onSubmit" :disabled="onProses">
                        <span v-if="form.processing" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Proses</span>
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AuthenticatedLayout>
</template>

<script setup>
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import SelectAccount from '@/Components/SelectAccount.vue';
import { Separator } from '@/Components/ui/separator';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { NumberField, NumberFieldContent, NumberFieldDecrement, NumberFieldIncrement, NumberFieldInput } from '@/components/ui/number-field';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow, TableFooter } from '@/Components/ui/table';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/Components/ui/alert-dialog';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

import { computed, reactive, ref, watch } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { useColorMode, useDebounceFn } from '@vueuse/core';
import ReusableTable from '@/Components/ReusableTable.vue';
import { Trash2 } from 'lucide-vue-next';
import { ReloadIcon } from '@radix-icons/vue';
import { toast } from 'vue-sonner';
import { formatCurrency } from '@/lib/utils';

const props = defineProps({});

const mode = useColorMode();

const items = ref([]);
const isLoading = ref(false);

const searchItems = useDebounceFn(async (search, loading) => {
    if (!search) return;
    isLoading.value = true;

    try {
        const response = await axios.get(route('item.search'), { params: { search: search } }, { preserveState: true });
        items.value = response.data.data;
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
}, 1000);

const onProses = ref(false);
const openAlertDialog = ref(false);
const form = useForm({
    transaction_date: new Date(),
    reference: null,
    description: null,
    transactions: [],
});

const addDetail = () => {
    form.transactions.push({
        item_id: null,
        type: 'in',
        quantity: 1,
    });
};

const removeDetail = (index) => {
    if (form.transactions.length > 1) {
        form.transactions.splice(index, 1); // Menghapus elemen di indeks yang benar
    }
};

const onSubmit = () => {
    form.post(route('transaction.store'), {
        preserveState: true,
        replace: true,
        async: true,
        onFinish: () => {
            openAlertDialog.value = false;
            onProses.value = false;
        },
        onSuccess: () => {
            toast.success('Data berhasil ditambahkan!');
        },
        onError: (errors) => {
            toast.error('Ada permasalahan saat menambah data, cek kembali inputan data');
        },
        onProgress: () => {
            onProses.value = true;
        },
    });
};
</script>
