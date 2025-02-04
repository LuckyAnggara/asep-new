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
                <Tabs :default-value="'header'" class="w-full">
                    <TabsList class="grid w-fit grid-cols-6">
                        <TabsTrigger value="header"> Header </TabsTrigger>
                        <TabsTrigger value="detail"> Detail </TabsTrigger>
                    </TabsList>
                    <TabsContent value="header">
                        <div
                            class="my-5 flex-1 flex-col items-center justify-center"
                        >
                            <ReusableTable
                                :columns="columnMaster"
                                :data="journalEntries.data"
                                :actions="actions"
                                :route-link="'journal-entries'"
                                :showing-limit="false"
                                :searching="true"
                                :filtering="filtering"
                            >
                            </ReusableTable>
                        </div>
                    </TabsContent>
                    <TabsContent value="detail">
                        <div
                            class="my-5 flex-1 flex-col items-center justify-center"
                        >
                            <ReusableTable
                                :columns="columnDetail"
                                :data="journalEntryDetails?.data"
                                :route-link="'journal-entries'"
                                :showing-limit="false"
                                :searching="true"
                                :filtering="filtering"
                            >
                            </ReusableTable>
                        </div>
                    </TabsContent>
                </Tabs>
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReusableTable from '@/Components/ReusableTable.vue';
import { router, Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs';
import DeleteDialog from '@/Components/DeleteDialog.vue';
import { computed, reactive, ref } from 'vue';
import { useMainStore } from '@/stores/main';

const props = defineProps({
    journal_entries: Object,
    journal_entry_details: Object,
});

const journalEntries = computed(() => props.journal_entries);
const journalEntryDetails = computed(() => props.journal_entry_details);

const mainStore = useMainStore();

const columnMaster = ref([
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

const columnDetail = ref([
    {
        label: 'Tanggal Transaksi',
        type: 'date',
        key: 'journal_entry',
        childKey: 'date',
    },
    {
        label: 'Referensi',
        type: 'string',
        key: 'journal_entry',
        childKey: 'reference',
    },
    {
        label: 'Nama Akun',
        type: 'string',
        key: 'chart_of_accounts',
        childKey: 'name',
    },
    {
        label: 'Debit',
        type: 'currency',
        key: 'debit',
    },
    {
        label: 'Credit',
        type: 'currency',
        key: 'credit',
    },

    {
        label: 'Deskripsi',
        type: 'string',
        key: 'journal_entry',
        childKey: 'description',
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
