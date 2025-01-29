<script setup>
const open = defineModel('open');
const data = defineModel('data');
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

import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

import { Textarea } from '@/components/ui/textarea';
import { ExclamationTriangleIcon } from '@radix-icons/vue';
import { vAutoAnimate } from '@formkit/auto-animate/vue';
import { Input } from '@/components/ui/input';
import { toast } from '@/components/ui/toast';
import { ReloadIcon } from '@radix-icons/vue';
import { Label } from '@/components/ui/label';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const form = useForm(data.value);

const isError = ref(false);
const bagError = ref({});
const isLoading = ref(false);

watch(open, (newValue) => {
    if (newValue == false) {
        isError.value = false;
        form.clearErrors();
    }
});

const onSubmit = () => {
    router.visit(route('account-category.update', data.value.id), {
        _token: page.props.csrf_token,
        preserveState: true,
        method: 'put',
        data: data.value,
        async: true,
        onStart: (x) => {
            isLoading.value = true;
        },
        onSuccess: () => {
            toast({
                title: 'Success',
                description: ` Data berhasil diperbarui dengan sukses`,
            });
            form.reset(['code', 'name', 'description']);
            isLoading.value = false;
            emit('close');
        },
        onError: (errors) => {
            isLoading.value = false;
            isError.value = true;
            bagError.value = errors;
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
    <div v-if="data">
        <Dialog v-model:open="open" as-child>
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Edit Data</DialogTitle>
                    <DialogDescription>
                        Edit data, pastikan data yang diinputkan benar.
                    </DialogDescription>
                </DialogHeader>

                <Alert variant="destructive" v-auto-animate v-if="isError">
                    <ExclamationTriangleIcon class="h-4 w-4" />
                    <AlertTitle>Error</AlertTitle>
                    <AlertDescription>
                        <ul>
                            <!-- Iterasi melalui semua error -->
                            <li v-for="(error, field) in bagError" :key="field">
                                {{ error }}
                            </li>
                        </ul>
                    </AlertDescription>
                </Alert>
                <form class="flex flex-col space-y-4">
                    <div class="grid gap-2">
                        <Label for="code">Kode Akun</Label>
                        <Input
                            id="code"
                            v-model="data.code"
                            type="text"
                            placeholder="Otomatis oleh sistem"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="name">Nama Sub Kategori Akun</Label>
                        <Input
                            id="name"
                            v-model="data.name"
                            type="text"
                            placeholder="Isi dengan nama Sub Kategori Akun"
                        />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Deskripsi</Label>
                        <Textarea
                            v-model="data.description"
                            class="resize-none"
                            placeholder="Isi dengan deskripsi, sebagai penjelas"
                        />
                    </div>
                </form>

                <DialogFooter>
                    <Button
                        type="button"
                        @click="emit('close')"
                        :disabled="isLoading"
                        variant="outline"
                    >
                        <span>Close</span>
                    </Button>
                    <Button type="button" @click="onSubmit()">
                        <span v-if="isLoading" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Update</span>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
