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
import { useForm } from '@inertiajs/vue3';
import { useToast } from '@/Components/ui/toast/use-toast';
import { ReloadIcon } from '@radix-icons/vue';

const open = defineModel('open');
const id = defineModel('id');

const emit = defineEmits(['close', 'submit']);
const props = defineProps({
    routeLink: {
        type: String,
        required: true,
    },
});

const form = useForm();

const { toast } = useToast();

const deleteItem = () => {
    form.delete(route(props.routeLink, id.value), {
        method: 'delete',
        async: true,
        preserveState: true,
        onSuccess: (x) => {
            toast({
                title: 'Success',
                description: `Data berhasil di hapus`,
                position,
            });
            emit('close');
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
</script>

<template>
    <AlertDialog v-model:open="open">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Apakah Anda yakin ingin menghapus data ini?</AlertDialogTitle>
                <AlertDialogDescription> Tindakan ini tidak dapat dibatalkan. Ini akan secara menghapus data ini dari server. </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <div class="flex flex-row space-x-2">
                    <Button type="button" variant="outline" @click="emit('close')" :disabled="form.processing">
                        <span>Cancel</span>
                    </Button>
                    <Button type="button" @click="deleteItem()" :disabled="form.processing">
                        <span v-if="form.processing" class="flex">
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
