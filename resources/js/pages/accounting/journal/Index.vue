<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Journal Entry</h1>
        </div>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <div class="flex-1 flex-col items-center justify-center">
                <Button class="mb-4"
                    ><Link :href="route('journal-entries.create')" as="button"
                        >Tambah Data</Link
                    ></Button
                >
                <ReusableTable
                    :columns="columns"
                    :data="journalEntries.data"
                    :actions="actions"
                    :route-link="'journal-entries'"
                    :showing-limit="false"
                    :searching="true"
                    :filtering="filtering"
                >
                </ReusableTable>
            </div>
            <DeleteDialog
                v-model:open="isDeleteDialogOpen"
                v-model:id="deleteId"
                :route-link="'journal-entries.destroy'"
                @close="isDeleteDialogOpen = false"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import ReusableTable from '@/components/ReusableTable.vue';
import { router, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import DeleteDialog from '@/components/DeleteDialog.vue';
import { computed, reactive, ref } from 'vue';
import { useMainStore } from '@/stores/main';

const props = defineProps({ journal_entries: Object });

const journalEntries = computed(() => props.journal_entries);
const mainStore = useMainStore();

const columns = ref([
    {
        label: 'Tanggal Transaksi',
        key: 'date',
    },
    { label: 'Referensi', key: 'reference' },
    { label: 'Deskripsi', key: 'description' },
    {
        label: 'Total Transaksi',
        key: 'total_nominal',
        type: 'currency',
    },
    {
        label: 'Tanggal Dibuat',
        key: 'created_at',
    },
]);

const filtering = reactive([
    {
        label: 'Tahun',
        key: new Date().getFullYear().toString(),
        default: new Date().getFullYear().toString(),
        query: 'year',
        type: 'select',
        options: mainStore.tahunOptions,
    },
    {
        label: 'Bulan',
        key: 'all',
        default: 'all',
        query: 'month',
        type: 'select',
        options: [{ label: 'Semua', value: 'all' }, ...mainStore.bulanOptions],
    },
    {
        label: 'Tanggal Transaksi',
        key: '',
        default: '',
        type: 'date',
        query: 'date',
    },
]);

const actions = ref([
    {
        label: 'Detail',
        handler: (item) => {
            router.visit(route('journal-entries.edit', item.id), {
                async: true,
                preserveState: true,
            });
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
const isDeleteDialogOpen = ref(false);
const deleteId = ref(null);
</script>
