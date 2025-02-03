<template>
    <div>
        <div class="flex items-center">
            <h1 class="my-4 text-lg font-semibold md:text-2xl">
                Account Category
            </h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center">
            <Button class="mb-4" @click="toCreate()">Tambah Data</Button>
            <ReusableTable
                :columns="columns"
                :data="data"
                :actions="actions"
                :route-link="'chart-of-accounts'"
                :showing-limit="false"
                :searching="false"
            />
        </div>
        <Create v-model="isCreateOpen" @close="isCreateOpen = false" />
        <Edit
            v-model:open="isEditOpen"
            v-model:data="editData"
            @close="isEditOpen = false"
            :data="editData"
        />
        <DeleteDialog
            v-model:open="isDeleteDialogOpen"
            v-model:id="deleteId"
            :route-link="link"
            @close="isDeleteDialogOpen = false"
        />
    </div>
</template>

<script setup>
import ReusableTable from '@/components/ReusableTable.vue';
import { Button } from '@/components/ui/button';
import Create from '@/Pages/ChartOfAccounts/components/CreateCategory.vue';
import Edit from '@/Pages/ChartOfAccounts/components/EditCategory.vue';
import DeleteDialog from '@/components/DeleteDialog.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const link = 'account-category.destroy';
const props = defineProps({
    data: {
        type: Array,
        default: () => null,
    },
});
const columns = ref([
    { label: 'Kode Akun', key: 'code', class: 'w-[180px]' },
    { label: 'Nama Akun', key: 'name' },
    { label: 'Deskripsi', key: 'description' },
    { label: 'Posisi', key: 'normal' },
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
