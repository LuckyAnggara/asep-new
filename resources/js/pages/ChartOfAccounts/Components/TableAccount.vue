<template>
    <div>
        <div class="flex items-center">
            <h1 class="my-4 text-lg font-semibold md:text-2xl">Chart Of Accounts (COA)</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center">
            <Button class="mb-4" @click="toCreate()">Tambah Data</Button>
            <div class="flex flex-col justify-between space-y-6">
                <div class="mb-4 flex flex-row space-x-6">
                    <div class="relative grid w-full items-center gap-1.5">
                        <Label>Search</Label>
                        <div class="relative w-full">
                            <Search class="absolute left-2 top-2.5 size-4 text-muted-foreground" />
                            <Input v-model="search" placeholder="Search" class="pl-8" />
                        </div>
                    </div>
                    <div class="w-18 relative grid items-center gap-1.5">
                        <Label>Filter</Label>
                        <Button type="button" @click="isSheetFilterOpen = !isSheetFilterOpen" variant="outline" size="icon">
                            <ListFilter :stroke-width="1.25" />
                        </Button>
                    </div>
                </div>
                <WhenVisible :data="accounts">
                    <template #fallback>
                        <div class="my-6 w-full text-center">Please wait</div>
                    </template>
                    <Table>
                        <TableHeader>
                            <TableRow class="font-bold uppercase">
                                <TableHead>No</TableHead>
                                <TableHead> Kategori </TableHead>
                                <TableHead> Sub Kategori </TableHead>
                                <TableHead> Kode Akun </TableHead>
                                <TableHead> Nama Akun </TableHead>
                                <TableHead> Deskripsi </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, index) in accounts" :key="item.id">
                                <TableCell>{{ index + 1 }} </TableCell>
                                <TableCell>{{ item.parent.category.name }} </TableCell>
                                <TableCell>{{ item.parent.name }} </TableCell>
                                <TableCell>
                                    <span>{{ item.code }} </span>
                                </TableCell>
                                <TableCell>
                                    <span>{{ item.name }} </span>
                                </TableCell>
                                <TableCell>
                                    <span>{{ item.description }} </span>
                                </TableCell>
                                <TableCell>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <EllipsisVertical class="cursor-pointer" :size="16" :stroke-width="0.5" />
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent class="w-24">
                                            <DropdownMenuLabel>Actions</DropdownMenuLabel>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuGroup>
                                                <DropdownMenuItem
                                                    class="cursor-pointer"
                                                    v-for="(action, actionIndex) in actions"
                                                    :key="actionIndex"
                                                    @click="action.handler(item)"
                                                >
                                                    <span>{{ action.label }}</span>
                                                </DropdownMenuItem>
                                            </DropdownMenuGroup>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </WhenVisible>
            </div>
        </div>
        <Create v-model="isCreateOpen" @close="isCreateOpen = false" />
        <Edit v-model:open="isEditOpen" v-model:data="editData" @close="isEditOpen = false" :data="editData" />
        <DeleteDialog v-model:open="isDeleteDialogOpen" v-model:id="deleteId" :route-link="'chart-of-accounts.destroy'" @close="isDeleteDialogOpen = false" />

        <Sheet v-model:open="isSheetFilterOpen">
            <SheetContent>
                <SheetHeader>
                    <SheetTitle>Filter Data</SheetTitle>
                    <SheetDescription></SheetDescription>
                </SheetHeader>
                <Separator />
                <div class="my-6 flex flex-col space-y-6">
                    <div class="grid items-center gap-1.5" v-for="(filter, index) in filtering" :key="index">
                        <Label>{{ filter.label }}</Label>
                        <VueDatePicker
                            :preview-format="'dd/MMM/yyyy'"
                            :format="'dd/MMM/yyyy'"
                            auto-apply
                            :enable-time-picker="false"
                            v-model="filter.key"
                            v-if="filter.type == 'date'"
                            :dark="mode == 'dark'"
                        ></VueDatePicker>

                        <Select v-model="filter.key" v-else>
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Show data" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="(item, index) in filter.options" :key="index" :value="item.value">
                                        {{ item.label }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
                <Separator class="mb-3" />
                <SheetFooter>
                    <SheetClose as-child>
                        <Button type="button" @click="resetData" variant="outline">
                            <span v-if="isLoading" class="flex">
                                <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                Please wait
                            </span>

                            <span class="flex flex-row" v-else> <X class="mr-2 h-4 w-4" /> Reset </span>
                        </Button>
                    </SheetClose>
                    <SheetClose as-child>
                        <Button type="button" @click="filteringData">
                            <span v-if="isLoading" class="flex">
                                <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                Please wait
                            </span>

                            <span class="flex flex-row space-x-4" v-else> <ListFilter /> <span>Filter</span></span>
                        </Button>
                    </SheetClose>
                </SheetFooter>
            </SheetContent>
        </Sheet>
    </div>
</template>

<script setup>
import ReusableTable from '@/Components/ReusableTable.vue';
import { WhenVisible, router } from '@inertiajs/vue3';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button';
import Edit from '@/Pages/ChartOfAccounts/Components/EditAccount.vue';
import Create from '@/Pages/ChartOfAccounts/Components/CreateAccount.vue';
import DeleteDialog from '@/Components/DeleteDialog.vue';
import { computed, reactive, ref } from 'vue';
import Separator from '@/components/ui/separator/Separator.vue';
import Label from '@/components/ui/label/Label.vue';
import { formatValue } from '@/lib/utils';
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuGroup,
    DropdownMenuItem,
} from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableHeader, TableRow, TableHead, TableCell } from '@/components/ui/table';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetClose, SheetFooter, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { EllipsisVertical, ListFilter, Search, X } from 'lucide-vue-next';
import { watchDebounced } from '@vueuse/core';
import { ReloadIcon } from '@radix-icons/vue';
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

const search = ref();
const isSheetFilterOpen = ref(false);
const isCreateOpen = ref(false);
const isEditOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const deleteId = ref(null);
const editData = ref({});
const isLoading = ref(false);
function toCreate() {
    isCreateOpen.value = true;
}

const paramater = computed(() => ({
    ...(search.value ? { search: search.value } : {}),
    ...(filtering == null
        ? {}
        : filtering.reduce((acc, filter) => {
              acc[filter.query] = filter.key;
              return acc;
          }, {})),
}));

watchDebounced(
    paramater,
    (newVal, oldVal) => {
        // Cek jika hanya properti tertentu yang berubah
        if (Object.keys(newVal).length === Object.keys(oldVal).length && newVal.search === oldVal.search) {
            return;
        }
        getData(paramater.value);
    },
    { debounce: 500, maxWait: 1000 },
);

const filteringData = () => {
    getData(paramater.value);
};

const getData = (data) => {
    router.visit(route('chart-of-accounts.index'), {
        data,
        preserveState: true,
        preserveScroll: true,
        // Hanya reset jika bukan pencarian
        onProgress: () => (isLoading.value = true),
        onFinish: () => (isLoading.value = false),
        onError: (errors) => {
            console.info(errors);
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
};
const resetData = () => {
    filtering.forEach((filter) => {
        filter.key = filter.default;
    });
    search.value = '';

    getData({});
};
</script>
