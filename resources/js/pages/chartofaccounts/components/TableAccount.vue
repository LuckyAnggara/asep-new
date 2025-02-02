<template>
    <div>
        <div class="flex items-center">
            <h1 class="my-4 text-lg font-semibold md:text-2xl">
                Chart Of Accounts (COA)
            </h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center">
            <Button class="mb-4" @click="toCreate()">Tambah Data</Button>
            <ReusableTable
                :columns="columns"
                :data="accounts.data"
                :actions="actions"
                :route-link="'chart-of-accounts'"
                :showing-limit="false"
                :searching="true"
                :filtering="filtering"
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
            :route-link="'chart-of-accounts.destroy'"
            @close="isDeleteDialogOpen = false"
        />
    </div>
</template>

<script setup>
import ReusableTable from '@/components/ReusableTable.vue';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Edit from '@/Pages/ChartOfAccounts/components/EditAccount.vue';
import Create from '@/Pages/ChartOfAccounts/components/CreateAccount.vue';
import DeleteDialog from '@/components/DeleteDialog.vue';
import { reactive, ref } from 'vue';

const props = defineProps({
    accounts: {
        type: Object,
        default: () => null,
    },
    category: {
        type: Array,
        default: () => null,
    },
    subCategory: {
        type: Array,
        default: () => null,
    },
});

const columns = ref([
    {
        label: 'Kategori',
        key: 'parent',
        childKey: 'category',
        childChildKey: 'name',
    },
    { label: 'Sub Kategori', key: 'parent', childKey: 'name' },
    { label: 'Tipe Cashflow', key: 'cashflow_type' },
    { label: 'Kode Akun', key: 'code', class: 'w-[120px]' },
    { label: 'Nama Akun', key: 'name' },
    { label: 'Deskripsi', key: 'description' },
]);

const filtering = reactive([
    {
        label: 'Kategori',
        key: 'all',
        default: 'all',
        query: 'category',
        options: [
            { label: 'Semua', value: 'all' },
            ...props.category.map((item) => ({
                label: item.name,
                value: item.id.toString(),
            })),
        ],
    },
    {
        label: 'Sub Kategori',
        key: 'all',
        default: 'all',
        query: 'sub_category',
        options: [
            { label: 'Semua', value: 'all' },
            ...props.subCategory?.map((item) => ({
                label: item.name,
                value: item.id.toString(),
            })),
        ],
    },
]);

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

const isCreateOpen = ref(false);
const isEditOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const deleteId = ref(null);
const editData = ref({});

function toCreate() {
    isCreateOpen.value = true;
}
</script>
