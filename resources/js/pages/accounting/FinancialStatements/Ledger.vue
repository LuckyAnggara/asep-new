<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Ledger</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <div class="d flex flex-row items-end space-x-2">
                <div>
                    <Label for="date">Daftar Account</Label>
                    <Popover v-model:open="openSelect">
                        <PopoverTrigger as-child>
                            <Button variant="outline" role="combobox" :class="cn('w-full justify-between', !showData && 'text-muted-foreground')">
                                Pilih Akun
                                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-full p-0">
                            <Command :multiple="true">
                                <CommandInput placeholder="Search language..." />
                                <CommandEmpty>Nothing found.</CommandEmpty>
                                <CommandList>
                                    <CommandGroup>
                                        <CommandItem
                                            class="cursor-pointer"
                                            v-for="item in accounts"
                                            :key="item.id"
                                            :value="item.name"
                                            @select="
                                                (value) => {
                                                    setBadge(item);
                                                    selectedStatus = status;
                                                }
                                            "
                                        >
                                            <Check :class="cn('mr-2 h-4 w-4', item.id === showData ? 'opacity-100' : 'opacity-0')" />
                                            {{ item.name }}
                                        </CommandItem>
                                    </CommandGroup>
                                </CommandList>
                            </Command>
                        </PopoverContent>
                    </Popover>
                </div>

                <div class="grid w-1/3 gap-2">
                    <Label for="date">Tanggal Data</Label>
                    <div class="flex flex-row space-x-3">
                        <VueDatePicker
                            :preview-format="'dd/MMM/yyyy'"
                            :format="'dd MMMM yyyy'"
                            auto-apply
                            v-model="startDate"
                            :enable-time-picker="false"
                            :dark="mode == 'dark'"
                        ></VueDatePicker>
                        <span>s.d</span>
                        <VueDatePicker
                            :preview-format="'dd/MMM/yyyy'"
                            :format="'dd MMMM yyyy'"
                            auto-apply
                            v-model="endDate"
                            :enable-time-picker="false"
                            :dark="mode == 'dark'"
                        ></VueDatePicker>
                    </div>
                </div>

                <div>
                    <Button type="button" @click="onSubmit()" :disabled="onProses">
                        <span v-if="onProses" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Submit</span>
                    </Button>
                </div>
            </div>
            <div class="my-6 flex min-h-12 flex-col space-y-2">
                <div class="grid grid-cols-6 gap-2">
                    <Badge
                        variant="secondary"
                        @click="removeSelect(index)"
                        class="flex cursor-pointer flex-row space-x-2"
                        v-for="(item, index) in items"
                        :key="item.id"
                        ><X size="12" /> <span>{{ item.name }}</span></Badge
                    >
                </div>

                <!-- <Select v-model="showData">
                    <SelectTrigger>
                        <SelectValue placeholder="Select a fruit" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            :value="data.id"
                            v-for="data in accounts"
                            :key="data.id"
                        >
                            {{ data.code }} |
                            {{ data.name }}
                        </SelectItem>
                    </SelectContent>
                </Select> -->
            </div>
            <!-- Date -->
            <div class="w-full" v-if="transactions.length > 0">
                <Tabs :default-value="0" class="flex w-full flex-row space-x-4">
                    <TabsList class="flex min-h-screen w-2/12 flex-col items-start justify-start space-y-2">
                        <TabsTrigger class="block h-12 w-full text-start" :value="index" v-for="(item, index) in transactions" :key="item.id">
                            <span class="items-start text-start">
                                {{ item.name }}
                            </span>
                        </TabsTrigger>
                    </TabsList>
                    <TabsContent
                        class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
                        :value="index"
                        v-for="(item, index) in transactions"
                        :key="item.id"
                    >
                        <div class="flex items-center">
                            <h1 class="text-lg font-semibold md:text-lg">
                                {{ item.name }}
                            </h1>
                        </div>
                        <div class="flex-1 flex-col items-center justify-center">
                            <ReusableTable
                                :table-height="'min-h-[200px]'"
                                :columns="columns"
                                :actions="actions"
                                :data="item.detail"
                                :route-link="'journal-entries'"
                                :showing-limit="false"
                                :searching="false"
                            >
                            </ReusableTable>
                        </div>
                    </TabsContent>
                </Tabs>
            </div>
        </div>

        <div></div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReusableTable from '@/Components/ReusableTable.vue';
import Badge from '@/Components/ui/badge/Badge.vue';
import { router, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

import { computed, reactive, ref, watch } from 'vue';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { X } from 'lucide-vue-next';
import { useColorMode } from '@vueuse/core';
import { ReloadIcon } from '@radix-icons/vue';

import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { FormControl, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';

import { toast } from '@/components/ui/toast';
import { cn } from '@/lib/utils';
import { Check, ChevronsUpDown } from 'lucide-vue-next';

const today = new Date();
const startOfYear = new Date(today.getFullYear(), 0, 1); // 1 Januari tahun ini

const startDate = ref(startOfYear);
const endDate = ref(today);

function setBadge(item) {
    if (!showData.value.includes(item.id)) {
        // Jika belum ada, tambahkan ke items.value dan showData.value
        items.value.push({ id: item.id, name: item.name });
        showData.value.push(item.id);
    }
}

const mode = useColorMode();

const props = defineProps({ accounts: Array, transactions: Object });
const items = ref([]);
const onProses = ref(false);
const showData = ref([]);
const openSelect = ref(false);

watch(showData, async (newQuestion, oldQuestion) => {
    console.info(newQuestion);
});

const columns = ref([
    {
        label: 'Tanggal Transaksi',
        key: 'journal_entry',
        childKey: 'date',
    },
    { label: 'Referensi', key: 'journal_entry', childKey: 'reference' },
    { label: 'Deskripsi', key: 'journal_entry', childKey: 'description' },
    { label: 'Debit', key: 'debit', type: 'currency' },
    { label: 'Kredit', key: 'credit', type: 'currency' },
    { label: 'Balance', key: 'balance', type: 'currency' },
]);

const actions = ref([
    {
        label: 'Detail',
        handler: (item) => {
            router.visit(route('journal-entries.edit', item.journal_entry.id), {
                async: true,
                preserveState: true,
            });
        },
    },
]);

function removeSelect(index) {
    items.value.splice(index, 1);
    showData.value.splice(index, 1);
    onSubmit();
}

const onSubmit = () => {
    router.visit(route('ledger.index'), {
        method: 'get',
        preserveState: true,
        data: {
            id: showData.value,
            start_date: startDate.value,
            end_date: endDate.value,
        },
        onStart() {
            onProses.value = true;
        },
        onSuccess() {
            onProses.value = false;
        },
    });
};
</script>
