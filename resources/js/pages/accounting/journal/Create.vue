<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Tambah Data</h1>
        </div>
        <div
            class="flex flex-col items-start rounded-lg border border-dashed p-6 shadow-sm lg:flex-row lg:space-x-6"
        >
            <form class="w-full space-y-4 lg:w-1/3">
                <div class="grid gap-2">
                    <Label for="email">Jenis Journal</Label>
                    <Select v-model="form.journal_type">
                        <SelectTrigger>
                            <SelectValue placeholder="Pilih Jenis Journal" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem value="umum">
                                    Journal Umum
                                </SelectItem>
                                <SelectItem value="ob">
                                    Begining Balance
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>

                <!-- Reference -->
                <div class="grid gap-2">
                    <Label for="reference">Referensi</Label>
                    <Input
                        :disabled="isOb"
                        id="reference"
                        v-model="form.reference"
                        type="text"
                        :placeholder="
                            isOb
                                ? 'Referensi akan dibuat otomatis'
                                : 'Masukkan Referensi'
                        "
                    />
                </div>

                <!-- Description -->
                <div class="grid gap-2">
                    <Label for="description">Deskripsi</Label>
                    <Textarea
                        v-model="form.description"
                        class="resize-none"
                        placeholder="Masukkan Deskripsi"
                    />
                </div>

                <!-- Date -->
                <div class="grid gap-2">
                    <Label for="date">Tanggal</Label>
                    <VueDatePicker
                        :preview-format="'dd/MMM/yyyy'"
                        :format="'dd MMMM yyyy'"
                        auto-apply
                        :enable-time-picker="false"
                        v-model="form.date"
                        :dark="mode == 'dark'"
                    ></VueDatePicker>
                </div>

                <!-- File Upload -->
                <div class="grid gap-2">
                    <Label for="file_upload">Lampiran</Label>
                    <Input
                        id="file_upload"
                        type="file"
                        @change="handleFileUpload"
                    />
                </div>

                <!-- Submit Button -->
                <Button
                    type="button"
                    @click="openAlertDialog = true"
                    :disabled="onProses"
                >
                    <span v-if="onProses" class="flex">
                        <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                        Please wait
                    </span>

                    <span v-else>Submit</span>
                </Button>
            </form>
            <div class="flex w-2/3 flex-col space-y-4">
                <div class="flex items-center">
                    <span class="text-lg font-semibold md:text-xl">
                        Detail
                    </span>
                </div>
                <div class="flex flex-col space-y-2">
                    <Button class="w-fit" type="button" @click="addDetail"
                        >Tambah</Button
                    >
                    <Table>
                        <TableHeader>
                            <TableRow class="font-bold uppercase">
                                <TableHead class="w-5/12">Nama Akun</TableHead>
                                <TableHead class="w-3/12">Debit</TableHead>
                                <TableHead class="w-3/12">Credit</TableHead>
                                <TableHead class="w-1/12"></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(item, i) in form.details"
                                :key="item.account_id"
                            >
                                <TableCell>
                                    <!-- <SelectAccount
                                    :data="accounts"
                                    @sendData="(x) => setData(x, i)"
                                /> -->
                                    <Select v-model="item.account_id">
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Pilih Akun"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                :value="data.id"
                                                v-for="(
                                                    data, index
                                                ) in accounts"
                                                :key="data.id"
                                                @click="setData(data, index)"
                                            >
                                                {{ data.code }} ||
                                                {{ data.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </TableCell>
                                <TableCell>
                                    <Input v-model="item.debit" />
                                </TableCell>
                                <TableCell>
                                    <Input v-model="item.credit" />
                                </TableCell>
                                <TableCell>
                                    <Button
                                        @click="removeDetail(i)"
                                        variant="ghost"
                                        size="icon"
                                    >
                                        <Trash2 class="h-6 w-6" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                        <TableFooter>
                            <TableRow class="font-bold">
                                <TableCell colspan="1" class="text-right"
                                    >Total</TableCell
                                >
                                <TableCell>{{
                                    formatCurrency(totalDebit)
                                }}</TableCell>
                                <TableCell>{{
                                    formatCurrency(totalCredit)
                                }}</TableCell>
                                <TableCell></TableCell>
                            </TableRow>
                        </TableFooter>
                    </Table>
                </div>
            </div>
        </div>
        <AlertDialog v-model:open="openAlertDialog">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Confirmation</AlertDialogTitle>
                    <AlertDialogDescription>
                        Data Anda akan dikirim. Periksa kembali apakah semuanya
                        sudah sesuai. Lanjutkan?
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <Button @click="onSubmit" :disabled="onProses">
                        <span v-if="onProses" class="flex">
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import SelectAccount from '@/Components/SelectAccount.vue';
import { Separator } from '@/Components/ui/separator';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';

import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
    TableFooter,
} from '@/Components/ui/table';
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
import { useColorMode } from '@vueuse/core';
import ReusableTable from '@/Components/ReusableTable.vue';
import { Trash2 } from 'lucide-vue-next';
import { ReloadIcon } from '@radix-icons/vue';
import { useToast } from '@/Components/ui/toast';
import { formatCurrency } from '@/lib/utils';

const { toast } = useToast();
const mode = useColorMode();
const page = usePage();
const props = defineProps({
    accounts: Array, // Daftar akun dari backend
});
const isOb = ref(false);
const onProses = ref(false);
const openAlertDialog = ref(false);
const fileError = ref(false);
const form = useForm({
    date: '',
    reference: '',
    description: '',
    attachment: '',
    journal_type: 'umum',
    details: [{ account_id: '', name: '', code: '', debit: 0, credit: 0 }],
});

function setData(item, index) {
    form.details[index].code = item.code;
    form.details[index].account_id = item.id;
}

const addDetail = () => {
    form.details.push({
        account_id: '',
        name: '',
        code: '',
        debit: 0,
        credit: 0,
    });
};

const removeDetail = (index) => {
    if (form.details.length > 1) {
        form.details.splice(index, 1); // Menghapus elemen di indeks yang benar
    }
};

const totalDebit = computed(() => {
    return form.details.reduce(
        (sum, item) => sum + (parseFloat(item.debit) || 0),
        0,
    );
});

const totalCredit = computed(() => {
    return form.details.reduce(
        (sum, item) => sum + (parseFloat(item.credit) || 0),
        0,
    );
});

const onSubmit = () => {
    const formData = new FormData();

    // Append form values to FormData
    formData.append('reference', form.reference || '');
    formData.append('journal_type', form.journal_type || '');
    formData.append(
        'date',
        new Date(form.date).toISOString().split('T')[0] || '',
    );
    formData.append('description', form.description || '');
    // Loop melalui details dan tambahkan setiap field sebagai key terpisah
    // formData.append('details', JSON.stringify(form.details));
    form.details.forEach((detail, index) => {
        formData.append(
            `details[${index}][chart_of_accounts_id]`,
            detail.account_id || '',
        );
        formData.append(`details[${index}][name]`, detail.name || '');
        formData.append(`details[${index}][code]`, detail.code || '');
        formData.append(`details[${index}][debit]`, detail.debit || 0);
        formData.append(`details[${index}][credit]`, detail.credit || 0);
    });
    // If additional fields like 'profile_pic' exist, you can append them
    if (form.attachment) {
        formData.append('attachment', form.attachment);
    }
    // form.post(route('members.store'), {
    router.visit('/accounting/journal-entries', {
        _token: page.props.csrf_token,
        method: 'post',
        preserveState: true,
        data: formData,
        replace: true,
        async: true,
        forceFormData: true,
        onFinish: () => {
            openAlertDialog.value = false;
            onProses.value = false;
        },
        onSuccess: () => {
            toast({
                title: 'Success',
                description: `Data berhasil di simpan`,
            });
        },
        onProgress: () => {
            onProses.value = true;
        },
        onError: (errors) => {
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
};

const handleFileUpload = (event) => {
    const selectedFile = event.target.files[0];
    if (selectedFile.size > 4096000) {
        fileError.value = true;
        toast({
            title: 'Error',
            description: 'File size is too large',
            variant: 'destructive',
        });
        if (!event.target.files[0]) {
            event.target.value = null;
        }
    } else {
        fileError.value = false;
        form.attachment = selectedFile;
    }
};

watch(
    () => form.journal_type,
    (x) => {
        if (x == 'ob') {
            form.reference = '';
            isOb.value = true;
        } else if (x == 'umum') {
            isOb.value = false;
        }
    },
);
</script>
