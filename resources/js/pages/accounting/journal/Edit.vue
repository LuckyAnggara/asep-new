<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Detail Data</h1>
        </div>
        <div
            v-auto-animate
            class="flex flex-col items-start rounded-lg border border-dashed p-6 shadow-sm lg:flex-row lg:space-x-6"
        >
            <form class="w-full space-y-4 lg:w-1/3">
                <!-- Reference -->
                <div class="grid gap-2">
                    <Label for="reference">Referensi</Label>
                    <Input
                        :readonly="!isEdit"
                        id="reference"
                        v-model="form.reference"
                        type="text"
                        placeholder="Masukkan Referensi"
                    />
                </div>

                <!-- Description -->
                <div class="grid gap-2">
                    <Label for="description">Deskripsi</Label>
                    <Textarea
                        :readonly="!isEdit"
                        v-model="form.description"
                        class="resize-none"
                        placeholder="Masukkan Deskripsi"
                    />
                </div>

                <!-- Date -->
                <div class="grid gap-2">
                    <Label for="date">Tanggal</Label>
                    <VueDatePicker
                        :readonly="!isEdit"
                        :preview-format="'dd/MMM/yyyy'"
                        :format="'dd MMMM yyyy'"
                        auto-apply
                        :enable-time-picker="false"
                        v-model="form.date"
                        :dark="mode == 'dark'"
                    ></VueDatePicker>
                </div>

                <!-- File Upload -->
                <div class="grid-2 grid gap-1.5" v-if="!data.attachment">
                    <Label for="file_upload">Lampiran</Label>
                    <Input
                        id="file_upload"
                        type="file"
                        @change="handleFileUpload"
                    />
                </div>
                <div v-else class="flex flex-row items-center justify-between">
                    <a
                        :href="`/storage/${data.attachment}`"
                        class="flex cursor-pointer flex-row space-x-1"
                        target="_blank"
                    >
                        <span class="text-blue-600 underline"> Lampiran </span>
                        <ExternalLink :size="16" :stroke-width="1.25" />
                    </a>
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        @click="removeLampiran()"
                    >
                        <Trash2 :size="16" :stroke-width="1.25" />
                    </Button>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-row space-x-2">
                    <Button
                        type="button"
                        variant="outline"
                        @click="isDeleteDialogOpen = true"
                        :disabled="onProses"
                    >
                        <Trash2 /> <span class="mr-2">Hapus</span>
                    </Button>
                    <Button
                        v-if="!isEdit"
                        type="button"
                        @click="isEdit = true"
                        :disabled="onProses"
                    >
                        <FilePenLine /> <span class="mr-2">Edit</span>
                    </Button>
                    <div v-else class="flex flex-row space-x-2">
                        <Button
                            type="button"
                            @click="isEdit = false"
                            :disabled="onProses"
                            variant="outline"
                        >
                            <span>Cancel</span>
                        </Button>
                        <Button
                            type="button"
                            @click="openAlertDialog = true"
                            :disabled="onProses"
                        >
                            <span v-if="onProses" class="flex">
                                <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                Please wait
                            </span>

                            <span v-else>Update</span>
                        </Button>
                    </div>
                </div>
            </form>
            <div class="flex w-2/3 flex-col space-y-4">
                <div class="flex items-center">
                    <span class="text-lg font-semibold md:text-xl">
                        Detail
                    </span>
                </div>
                <div class="flex flex-col space-y-2">
                    <Button
                        class="w-fit"
                        type="button"
                        @click="addDetail"
                        :class="isEdit ? 'block' : 'hidden'"
                        >Tambah</Button
                    >
                    <Table>
                        <TableHeader>
                            <TableRow class="font-bold uppercase">
                                <TableHead class="w-5/12">Nama Akun</TableHead>
                                <TableHead class="w-3/12">Debit</TableHead>
                                <TableHead class="w-3/12">Credit</TableHead>
                                <TableHead
                                    class="hidden w-1/12"
                                    :class="isEdit ? 'hidden' : 'block'"
                                ></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(item, i) in form.details"
                                :key="item.chart_of_accounts_id"
                            >
                                <TableCell>
                                    <Select
                                        :disabled="!isEdit"
                                        v-model="item.chart_of_accounts_id"
                                    >
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Select a fruit"
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
                                    <NumberField
                                        :disabled="!isEdit"
                                        v-model="item.debit"
                                        :default-value="0"
                                        :format-options="{
                                            style: 'currency',
                                            currency:
                                                page.props.auth.company
                                                    .currency,
                                            currencyDisplay: 'code',
                                            currencySign: 'accounting',
                                        }"
                                    >
                                        <NumberFieldContent>
                                            <NumberFieldInput />
                                        </NumberFieldContent>
                                    </NumberField>
                                </TableCell>
                                <TableCell>
                                    <NumberField
                                        :disabled="!isEdit"
                                        v-model="item.credit"
                                        :default-value="0"
                                        :format-options="{
                                            style: 'currency',
                                            currency:
                                                page.props.auth.company
                                                    .currency,
                                            currencyDisplay: 'code',
                                            currencySign: 'accounting',
                                        }"
                                    >
                                        <NumberFieldContent>
                                            <NumberFieldInput />
                                        </NumberFieldContent>
                                    </NumberField>
                                    <!-- <Input
                                        v-model="item.credit"
                                        :readonly="!isEdit"
                                    /> -->
                                </TableCell>
                                <TableCell
                                    :class="!isEdit ? 'hidden' : 'block'"
                                >
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
                        Data Anda akan diupdate. Periksa kembali apakah semuanya
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
        <DeleteDialog
            v-model:open="isDeleteDialogOpen"
            v-model:id="form.id"
            :route-link="'journal-entries.destroy'"
            @close="isDeleteDialogOpen = false"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { vAutoAnimate } from '@formkit/auto-animate';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import SelectAccount from '@/components/SelectAccount.vue';
import { Separator } from '@/components/ui/separator';
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@/components/ui/number-field';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

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
} from '@/components/ui/alert-dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import DeleteDialog from '@/components/DeleteDialog.vue';
import { computed, reactive, ref } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import ReusableTable from '@/components/ReusableTable.vue';
import { ExternalLink, FilePenLine, Trash2, X } from 'lucide-vue-next';
import { ReloadIcon } from '@radix-icons/vue';
import { useToast } from '@/components/ui/toast';
import { formatCurrency } from '@/lib/utils';

const { toast } = useToast();

const mode = useColorMode();
const page = usePage();
const props = defineProps({
    journal_entry: Object,
    accounts: Array, // Daftar akun dari backend
});

const data = computed(() => props.journal_entry);
const onProses = ref(false);
const openAlertDialog = ref(false);
const fileError = ref(false);
const isDeleteDialogOpen = ref(false);

const form = useForm({
    id: props.journal_entry.id,
    reference: props.journal_entry.reference,
    date: props.journal_entry.date,
    description: props.journal_entry.description,
    attachment: null, // Hanya untuk file baru
    details: props.journal_entry.details.map((detail) => ({
        id: detail.id,
        chart_of_accounts_id: detail.chart_of_accounts_id,
        debit: detail.debit,
        credit: detail.credit,
    })),
});

function setData(item, index) {
    form.details[index].code = item.code;
    form.details[index].chart_of_accounts_id = item.id;
}

const addDetail = () => {
    form.details.push({
        chart_of_accounts_id: '',
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
    return form.details?.reduce(
        (sum, item) => sum + (parseFloat(item.debit) || 0),
        0,
    );
});

const totalCredit = computed(() => {
    return form.details?.reduce(
        (sum, item) => sum + (parseFloat(item.credit) || 0),
        0,
    );
});

const onSubmit = () => {
    const formData = new FormData();

    // Append form values to FormData
    formData.append('_method', 'put');
    formData.append('reference', form.reference || '');
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
            detail.chart_of_accounts_id || '',
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
    router.post(`/accounting/journal-entries/${form.id}`, formData, {
        _token: page.props.csrf_token,
        preserveState: true,
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
                description: `Data berhasil di update`,
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

// Edit
const isEdit = ref(false);

function removeLampiran() {
    data.value.attachment = null;
}
</script>
