<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Ledger</h1>
        </div>
        <div class="flex w-full flex-row items-end space-x-4">
            <div class="grid w-2/3 gap-2">
                <Label for="date">Account</Label>
                <Select v-model="showData">
                    <SelectTrigger>
                        <SelectValue placeholder="Select a fruit" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            :value="data.id"
                            v-for="data in account"
                            :key="data.id"
                        >
                            {{ data.code }} |
                            {{ data.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <!-- Date -->
            <div class="grid w-1/3 gap-2">
                <Label for="date">Tanggal</Label>
                <VueDatePicker
                    :preview-format="'dd/MMM/yyyy'"
                    :format="'dd MMMM yyyy'"
                    auto-apply
                    v-model="date"
                    :enable-time-picker="false"
                    :dark="mode == 'dark'"
                ></VueDatePicker>
            </div>
            <Button type="button" @click="onSubmit()" :disabled="onProses">
                <span v-if="onProses" class="flex">
                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                    Please wait
                </span>

                <span v-else>Submit</span>
            </Button>
        </div>

        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <div class="flex items-center">
                <h1 class="text-lg font-semibold md:text-lg">Ledger</h1>
            </div>
            <div class="flex-1 flex-col items-center justify-center">
                <ReusableTable
                    :table-height="'min-h-[200px]'"
                    :columns="columns"
                    :data="transactions"
                    :route-link="'journal-entries'"
                    :showing-limit="false"
                    :searching="false"
                >
                </ReusableTable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import ReusableTable from '@/components/ReusableTable.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import { router, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

import { computed, reactive, ref } from 'vue';
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
} from '@/components/ui/tags-input';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { X } from 'lucide-vue-next';
import { useColorMode } from '@vueuse/core';
import { ReloadIcon } from '@radix-icons/vue';

const mode = useColorMode();
const props = defineProps({ account: Array, transactions: Array });

const onProses = ref(false);
const showData = ref();
const date = ref();

const columns = ref([
    {
        label: 'Tanggal Transaksi',
        key: 'journal_entry',
        childKey: 'date',
    },
    { label: 'Deskripsi', key: 'journal_entry', childKey: 'description' },
    { label: 'Debit', key: 'debit', type: 'currency' },
    { label: 'Kredit', key: 'kredit', type: 'currency' },
    { label: 'Balance', key: 'balance', type: 'currency' },
]);

const onSubmit = () => {
    // const queryString = showData.value
    //     .map((id) => `account_id[]=${id}`)
    //     .join('&');
    router.visit(route('ledger.index'), {
        method: 'get',
        preserveState: true,
        data: {
            id: [showData.value],
            date: date.value,
        },

        // onError: (errors) => {
        //     toast({
        //         title: 'Error',
        //         description: errors,
        //         variant: 'destructive',
        //     });
        // },
    });
};
</script>
