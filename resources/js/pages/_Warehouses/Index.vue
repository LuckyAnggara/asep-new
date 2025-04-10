<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Warehouses</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <div class="flex-1 flex-col items-center justify-center">
                <Button class="mb-4" @click="toCreate()">Tambah Data</Button>
                <ReusableTable :columns="columns" :data="warehouses" :actions="actions" :route-link="'warehouse'" :showing-limit="false" :searching="false" />
            </div>
            <Create v-model="isCreateOpen" @close="isCreateOpen = false" />
            <Edit v-model:open="isEditOpen" v-model:data="editData" @close="isEditOpen = false" :data="editData" />
            <DeleteDialog v-model:open="isDeleteDialogOpen" v-model:id="deleteId" :route-link="link" @close="isDeleteDialogOpen = false" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import ReusableTable from '@/components/ReusableTable.vue';
import { Button } from '@/components/ui/button';
import Create from './Create.vue';
import Edit from './Edit.vue';
import DeleteDialog from '@/components/DeleteDialog.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner'; // ShadCN Vue toast notification

const link = 'warehouse.destroy';
const props = defineProps({
    warehouses: Array,
});
const columns = ref([
    { label: 'Nama', key: 'name' },
    { label: 'Lokasi', key: 'location' },
    { label: 'Deskripsi', key: 'description' },
]);

const isCreateOpen = ref(false);
const isEditOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const deleteId = ref(null);
const editData = ref({});

function toCreate() {
    isCreateOpen.value = true;
}
const actions = ref([
    {
        label: 'Detail',
        handler: (item) => {
            editData.value = item;
            isEditOpen.value = true;
        },
    },
    {
        label: 'Delete',
        handler: (item) => {
            deleteId.value = item.id;
            isDeleteDialogOpen.value = true;
        },
    },
]);
</script>
