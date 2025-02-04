<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Ledger</h1>
        </div>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <div class="flex w-2/3 flex-col space-y-2">
                <Label for="date">Daftar Account</Label>
                <Popover v-model:open="openSelect">
                    <PopoverTrigger as-child>
                        <Button
                            variant="outline"
                            role="combobox"
                            :class="
                                cn(
                                    'w-full justify-between',
                                    !showData && 'text-muted-foreground',
                                )
                            "
                        >
                            Pilih Akun
                            <ChevronsUpDown
                                class="ml-2 h-4 w-4 shrink-0 opacity-50"
                            />
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
                                                openSelect = false;
                                            }
                                        "
                                    >
                                        <Check
                                            :class="
                                                cn(
                                                    'mr-2 h-4 w-4',
                                                    item.id === showData
                                                        ? 'opacity-100'
                                                        : 'opacity-0',
                                                )
                                            "
                                        />
                                        {{ item.name }}
                                    </CommandItem>
                                </CommandGroup>
                            </CommandList>
                        </Command>
                    </PopoverContent>
                </Popover>
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
            <div class="grid w-1/3 gap-2">
                <Label for="date">Tanggal Data</Label>
                <VueDatePicker
                    :preview-format="'dd/MMM/yyyy'"
                    :format="'dd MMMM yyyy'"
                    auto-apply
                    range
                    v-model="date"
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
        <div>
            <Tabs :default-value="'1'" class="w-full">
                <TabsList class="grid w-fit grid-cols-6">
                    <TabsTrigger
                        :value="item.id"
                        v-for="item in transactions"
                        :key="item.id"
                    >
                        {{ item.name }}
                    </TabsTrigger>
                </TabsList>
                <TabsContent
                    class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
                    :value="item.id"
                    v-for="item in transactions"
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

        <div></div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import ReusableTable from '@/components/ReusableTable.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import { router, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

import { computed, reactive, ref, watch } from 'vue';
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
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { X } from 'lucide-vue-next';
import { useColorMode } from '@vueuse/core';
import { ReloadIcon } from '@radix-icons/vue';

import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
} from '@/components/ui/form';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';

import { toast } from '@/components/ui/toast';
import { cn } from '@/lib/utils';
import { Check, ChevronsUpDown } from 'lucide-vue-next';

const languages = [
    { label: 'English', value: 'en' },
    { label: 'French', value: 'fr' },
    { label: 'German', value: 'de' },
    { label: 'Spanish', value: 'es' },
    { label: 'Portuguese', value: 'pt' },
    { label: 'Russian', value: 'ru' },
    { label: 'Japanese', value: 'ja' },
    { label: 'Korean', value: 'ko' },
    { label: 'Chinese', value: 'zh' },
];

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
const date = ref();
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
            date: date.value,
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
