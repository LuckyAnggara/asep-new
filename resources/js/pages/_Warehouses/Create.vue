<script setup>
const model = defineModel({ default: false });
const emit = defineEmits(['close']);

import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Alert, AlertTitle, AlertDescription } from '@/components/ui/alert';

import { Textarea } from '@/components/ui/textarea';
import { ExclamationTriangleIcon } from '@radix-icons/vue';
import { vAutoAnimate } from '@formkit/auto-animate/vue';
import { Input } from '@/components/ui/input';
import { Toaster, toast } from 'vue-sonner';
import { ReloadIcon } from '@radix-icons/vue';
import { Label } from '@/components/ui/label';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();

const form = useForm({
    name: '',
    location: '',
    description: '',
});

const isError = ref(false);
watch(model, (newValue) => {
    if (newValue == false) {
        isError.value = false;
        form.clearErrors();
    }
});
const onSubmit = () => {
    // form.post(route('members.store'), {
    form.post(route('warehouse.store'), {
        _token: page.props.csrf_token,
        preserveState: true,
        onFinish: () => {
            emit('close');
        },
        onSuccess: () => {
            toast.success('Data berhasil ditambahkan!');
            form.reset();
            emit('close');
        },
        onError: (errors) => {
            isError.value = true;
            toast.error(errors.name?.[0] || 'Failed to add warehouse');
        },
    });
};
</script>

<template>
    <div>
        <Toaster position="top-right" />
        <Dialog v-model:open="model" as-child>
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Tambah Data</DialogTitle>
                    <DialogDescription> Tambah data baru, pastikan data yang diinputkan benar. </DialogDescription>
                </DialogHeader>

                <Alert variant="destructive" v-auto-animate v-if="isError">
                    <ExclamationTriangleIcon class="h-4 w-4" />
                    <AlertTitle>Error</AlertTitle>
                    <AlertDescription>
                        <ul>
                            <!-- Iterasi melalui semua error -->
                            <li v-for="(error, field) in form.errors" :key="field">
                                {{ error }}
                            </li>
                        </ul>
                    </AlertDescription>
                </Alert>
                <form class="flex flex-col space-y-4">
                    <div class="grid gap-2">
                        <Label for="code">Nama Gudang</Label>
                        <Input id="code" v-model="form.name" type="text" placeholder="Isi dengan nama gudang" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Lokasi</Label>
                        <Input id="name" v-model="form.location" type="text" placeholder="Isi dengan lokasi" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Deskripsi</Label>
                        <Textarea v-model="form.description" class="resize-none" placeholder="Isi dengan deskripsi, sebagai penjelas" />
                    </div>
                </form>

                <DialogFooter>
                    <Button type="button" @click="onSubmit()" :disabled="form.processing">
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
