<script setup>
const model = defineModel({ default: false });
const emit = defineEmits(['close']);

import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { ExclamationTriangleIcon } from '@radix-icons/vue';
import { vAutoAnimate } from '@formkit/auto-animate/vue';
import { Input } from '@/components/ui/input';
import { toast } from '@/components/ui/toast';
import { ReloadIcon } from '@radix-icons/vue';
import { Label } from '@/components/ui/label';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const category = computed(() => page.props.category);

const form = useForm({
    code: null,
    name: null,
    category_id: null,
    description: null,
});

const isLoading = ref(false);
const isError = ref(false);
watch(model, (newValue) => {
    if (newValue == false) {
        isError.value = false;
        form.clearErrors();
    }
});
const onSubmit = () => {
    // form.post(route('members.store'), {
    form.post('/account-sub-category', {
        _token: page.props.csrf_token,
        preserveState: true,
        onFinish: () => {
            emit('close');
        },
        onSuccess: () => {
            toast({
                title: 'Success',
                description: `Data berhasil di simpan`,
            });
            form.reset(['category_id', 'name', 'description']);
            emit('close');
        },
        onError: (errors) => {
            isError.value = true;
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
};
</script>

<template>
    <div>
        <Dialog v-model:open="model" as-child>
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Tambah Data</DialogTitle>
                    <DialogDescription>
                        Tambah data baru, pastikan data yang diinputkan benar.
                    </DialogDescription>
                </DialogHeader>

                <Alert variant="destructive" v-auto-animate v-if="isError">
                    <ExclamationTriangleIcon class="h-4 w-4" />
                    <AlertTitle>Error</AlertTitle>
                    <AlertDescription>
                        <ul>
                            <!-- Iterasi melalui semua error -->
                            <li
                                v-for="(error, field) in form.errors"
                                :key="field"
                            >
                                {{ error }}
                            </li>
                        </ul>
                    </AlertDescription>
                </Alert>
                <form class="flex flex-col space-y-4">
                    <div class="grid gap-2">
                        <Label for="code">Kode Akun</Label>
                        <Input
                            readonly
                            id="code"
                            v-model="form.code"
                            type="text"
                            placeholder="Otomatis oleh sistem"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="email">Kategori</Label>
                        <Select v-model="form.category_id">
                            <SelectTrigger>
                                <SelectValue
                                    placeholder="Pilih Kategori Akun"
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(item, index) in category"
                                        :key="index"
                                        :value="item.id.toString()"
                                    >
                                        {{ item.code }} - {{ item.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="name">Nama Sub Kategori Akun</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Isi dengan nama Sub Kategori Akun"
                        />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Deskripsi</Label>
                        <Textarea
                            v-model="form.description"
                            class="resize-none"
                            placeholder="Isi dengan deskripsi, sebagai penjelas"
                        />
                    </div>
                </form>

                <DialogFooter>
                    <Button
                        type="button"
                        @click="onSubmit()"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Submit</span>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
