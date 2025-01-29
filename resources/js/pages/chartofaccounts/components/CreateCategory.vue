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
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
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

import { Input } from '@/components/ui/input';
import { toast } from '@/components/ui/toast';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm as formValidate } from 'vee-validate';
import { ReloadIcon } from '@radix-icons/vue';

import { router, useForm, usePage } from '@inertiajs/vue3';
import { h, ref } from 'vue';
import * as z from 'zod';

const page = usePage();
const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(50),
        code: z.string().min(1).max(50),
        description: z.string(),
    }),
);

const { isFieldDirty, handleSubmit } = formValidate({
    validationSchema: formSchema,
});

const onProses = ref(false);
const onSubmit = handleSubmit((values) => {
    // form.post(route('members.store'), {
    router.visit(route('account-category.store'), {
        _token: page.props.csrf_token,
        method: 'post',
        preserveState: true,
        data: values,
        replace: true,
        forceFormData: true,
        onFinish: () => {
            onProses.value = false;
            emit('close');
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
            console.info(errors);
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
});
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

                <form id="dialogFormCategory" class="flex flex-col space-y-4">
                    <FormField v-slot="{ componentField }" name="code">
                        <FormItem>
                            <FormLabel>Kode Akun</FormLabel>
                            <FormControl>
                                <Input
                                    type="texr"
                                    placeholder="Kode Akun"
                                    v-bind="componentField"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="name">
                        <FormItem>
                            <FormLabel>Nama Akun</FormLabel>
                            <FormControl>
                                <Input
                                    type="text"
                                    placeholder="Nama Akun"
                                    v-bind="componentField"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField
                        v-slot="{ componentField }"
                        name="description"
                        :validate-on-blur="!isFieldDirty"
                    >
                        <FormItem>
                            <FormLabel>Description</FormLabel>
                            <FormControl>
                                <Textarea
                                    class="resize-none"
                                    placeholder="Isi dengan alamat lengkap"
                                    v-bind="componentField"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                </form>

                <DialogFooter>
                    <Button
                        type="button"
                        @click="onSubmit()"
                        :disabled="onProses"
                    >
                        <span v-if="onProses" class="flex">
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
