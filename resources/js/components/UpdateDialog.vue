<script setup>
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
import { Button } from '@/components/ui/button';
import { router, useForm } from '@inertiajs/vue3';
// import { useToast } from '@/Components/ui/toast/use-toast';
import { ReloadIcon } from '@radix-icons/vue';
import { reactive, ref } from 'vue';
import { Toaster, toast } from 'vue-sonner';

const open = defineModel('open');
const id = defineModel('id');

const emit = defineEmits(['close', 'submit']);
const props = defineProps({
    routeLink: {
        type: String,
        required: true,
    },
    data: Object,
});

const form = reactive(props.data);
const isLoading = ref(false);

const update = () => {
    console.info(form);

    router.post(`/inventory/item/${id.value}`, form, {
        preserveScroll: true,
        preserveState: true,
        async: true,
        onStart: (x) => {
            isLoading.value = true;
        },
        onSuccess: (x) => {
            isLoading.value = false;
            toast.success('Perubahan berhasil disimpan!');
            emit('close');
        },
        onError: (errors) => {
            isLoading.value = false;
            console.info(errors);
            toast.error('Opss, Ada permasalahan');
        },
    });
};
</script>

<template>
    <!-- <Toaster position="bottom-center" /> -->

    <AlertDialog v-model:open="open">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Confirmation?</AlertDialogTitle>
                <AlertDialogDescription> Tindakan ini tidak dapat dibatalkan. </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <div class="flex flex-row space-x-2">
                    <Button type="button" variant="outline" @click="emit('close')" :disabled="isLoading">
                        <span>Cancel</span>
                    </Button>
                    <Button type="button" @click="update()" :disabled="isLoading">
                        <span v-if="isLoading" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Submit</span>
                    </Button>
                </div>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
