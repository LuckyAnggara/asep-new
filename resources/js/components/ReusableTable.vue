<template>
    <div>
        <div class="mb-4 flex flex-row space-x-6">
            <div class="grid items-center gap-1.5" v-if="showingLimit">
                <Label>Show</Label>
                <Select v-model="limit">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Show data" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem
                                v-for="(item, index) in limits"
                                :key="index"
                                :value="item.value"
                            >
                                {{ item.label }}
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
            <div
                class="relative grid w-full items-center gap-1.5"
                v-if="searching"
            >
                <Label>Search</Label>
                <div class="relative w-full">
                    <Search
                        class="absolute left-2 top-2.5 size-4 text-muted-foreground"
                    />
                    <Input v-model="search" placeholder="Search" class="pl-8" />
                </div>
            </div>
            <div
                class="w-18 relative grid items-center gap-1.5"
                v-if="filtering"
            >
                <Label>Filter</Label>
                <Button
                    type="button"
                    @click="isSheetFilterOpen = !isSheetFilterOpen"
                    variant="outline"
                    size="icon"
                >
                    <ListFilter :stroke-width="1.25" />
                </Button>
            </div>
        </div>
        <WhenVisible :data="data">
            <template #fallback>
                <div class="my-6 w-full text-center">Loading data...</div>
            </template>
            <div
                class="flex flex-col justify-between space-y-6"
                :class="tableHeight"
            >
                <Table>
                    <TableHeader>
                        <TableRow class="font-bold uppercase">
                            <TableHead>No</TableHead>
                            <TableHead
                                v-for="column in columns"
                                :key="column.key"
                            >
                                {{ column.label }}
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="(item, index) in data"
                            :key="index"
                            v-memo="[item]"
                        >
                            <TableCell>
                                {{ getRowNumber(index) }}
                            </TableCell>
                            <TableCell
                                v-for="column in columns"
                                :key="column.key"
                                :class="column.class"
                            >
                                <span>
                                    {{ formatValue(item, column) }}
                                </span>
                            </TableCell>

                            <TableCell v-if="actions">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <EllipsisVertical
                                            class="cursor-pointer"
                                            :size="16"
                                            :stroke-width="0.5"
                                        />
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent class="w-24">
                                        <DropdownMenuLabel
                                            >Actions</DropdownMenuLabel
                                        >
                                        <DropdownMenuSeparator />
                                        <DropdownMenuGroup>
                                            <DropdownMenuItem
                                                class="cursor-pointer"
                                                v-for="(
                                                    action, actionIndex
                                                ) in actions"
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
                <!-- <Pagination
                    v-if="pagination"
                    :total="pagination.total"
                    :itemsPerPage="pagination.itemsPerPage"
                    :default-page="pagination.currentPage"
                >
                    <PaginationList
                        v-slot="{ items }"
                        class="flex items-center gap-1"
                    >
                        <PaginationFirst @click="pagination.onFirstPage()" />
                        <PaginationPrev @click="pagination.onPrevPage()" />
                        <template v-for="(item, index) in items">
                            <PaginationListItem
                                v-if="item.type === 'page'"
                                :key="index"
                                :value="item.value"
                                as-child
                            >
                                <Button
                                    @click="toPage(item.value)"
                                    class="h-10 w-10 p-0"
                                    :variant="
                                        item.value === pagination.currentPage
                                            ? 'default'
                                            : 'outline'
                                    "
                                >
                                    {{ item.value }}
                                </Button>
                            </PaginationListItem>
                            <PaginationEllipsis
                                v-else
                                :key="item.type"
                                :index="index"
                            />
                        </template>
                        <PaginationNext @click="pagination.onNextPage()" />
                        <PaginationLast @click="pagination.onLastPage()" />
                    </PaginationList>
                </Pagination> -->
            </div>
        </WhenVisible>

        <Sheet v-model:open="isSheetFilterOpen">
            <SheetContent>
                <SheetHeader>
                    <SheetTitle>Filter Data</SheetTitle>
                    <SheetDescription></SheetDescription>
                </SheetHeader>
                <Separator />
                <div class="my-6 flex flex-col space-y-6">
                    <div
                        class="grid items-center gap-1.5"
                        v-for="(filter, index) in filtering"
                        :key="index"
                    >
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
                                    <SelectItem
                                        v-for="(item, index) in filter.options"
                                        :key="index"
                                        :value="item.value"
                                    >
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
                        <Button
                            type="button"
                            @click="resetData()"
                            variant="outline"
                        >
                            <span v-if="isLoading" class="flex">
                                <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                Please wait
                            </span>

                            <span class="flex flex-row" v-else>
                                <X class="mr-2 h-4 w-4" /> Reset
                            </span>
                        </Button>
                    </SheetClose>
                    <SheetClose as-child>
                        <Button type="button" @click="searchData()">
                            <span v-if="isLoading" class="flex">
                                <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                Please wait
                            </span>

                            <span class="flex flex-row space-x-4" v-else>
                                <ListFilter /> <span>Filter</span></span
                            >
                        </Button>
                    </SheetClose>
                </SheetFooter>
            </SheetContent>
        </Sheet>
    </div>
</template>

<script setup>
/**
 * @prop {Array} columns - Kolom-kolom yang akan ditampilkan dalam tabel.
 * @prop {Array} data - Data yang akan ditampilkan dalam tabel.
 * @prop {Object} pagination - Konfigurasi pagination.
 * @prop {Boolean} showingLimit - Menampilkan dropdown untuk memilih jumlah data per halaman.
 * @prop {Boolean} searching - Menampilkan input pencarian.
 * @prop {Array} actions - Aksi-aksi yang dapat dilakukan pada setiap baris data.
 * @prop {String} tableHeight - Tinggi tabel.
 * @prop {String} routeLink - URL untuk routing.
 * @prop {Object} filtering - Konfigurasi filter.
 */

import { computed, ref } from 'vue';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetClose,
    SheetFooter,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import {
    Table,
    TableBody,
    TableHeader,
    TableRow,
    TableHead,
    TableCell,
} from '@/components/ui/table';
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/components/ui/pagination';
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuGroup,
    DropdownMenuItem,
} from '@/components/ui/dropdown-menu';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';

import { EllipsisVertical, Search, ListFilter, X } from 'lucide-vue-next';
import { ReloadIcon } from '@radix-icons/vue';
import { WhenVisible, router, usePage } from '@inertiajs/vue3';
import { useColorMode, watchDebounced } from '@vueuse/core';
import { useToast } from '@/components/ui/toast/use-toast';

const mode = useColorMode();

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        default: () => ({
            total: 0,
            itemsPerPage: 10,
            currentPage: 1,
            onFirstPage: () => {},
            onPrevPage: () => {},
            onNextPage: () => {},
            onLastPage: () => {},
            onPageChange: () => {},
        }),
    },
    actions: {
        type: Array,
        default: () => [],
    },
    filtering: {
        type: Object,
        default: () => null,
    },
    showingLimit: {
        type: Boolean,
        default: true,
    },
    searching: {
        type: Boolean,
        default: true,
    },
    tableHeight: {
        type: String,
        default: 'min-h-[600px]',
    },
    routeLink: {
        type: String,
        default: null,
    },
});
const { toast } = useToast();
const isLoading = ref(false);
const page = usePage();
const getRowNumber = (index) => {
    if (!props.pagination) return index + 1; // Jika tidak ada pagination, gunakan index biasa
    const { currentPage, itemsPerPage } = props.pagination;
    return (currentPage - 1) * itemsPerPage + index + 1;
};

const updateRoute = (additionalData = {}) => {
    props.filtering.forEach((filter) => {
        filter.key = paramater.value[filter.query];
    });

    router.visit(props.routeLink, {
        data: paramater.value,
        preserveState: true,
        onProgress: () => {
            isLoading.value = true;
        },
        onFinish: () => {
            isLoading.value = false;
        },
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

const paramater = computed(() => {
    return {
        limit: limit.value,
        search: search.value,
        ...(props.filtering == null
            ? {}
            : props.filtering.reduce((acc, filter) => {
                  acc[filter.query] = filter.key;
                  return acc;
              }, {})),
    };
});

const limits = ref([
    { label: '10', value: '10' },
    { label: '20', value: '20' },
    { label: '50', value: '50' },
    { label: '100', value: '100' },
]);

const limit = ref(route().params.limit?.toString() ?? '10');
const search = ref(route().params.search?.toString() ?? '');

watchDebounced(
    paramater,
    (newVal, oldVal) => {
        console.info(newVal);
        // Check if only limit or search property has changed
        if (
            Object.keys(newVal).length === Object.keys(oldVal).length &&
            newVal.limit === oldVal.limit &&
            newVal.search === oldVal.search
        ) {
            return;
        }
        searchData();
    },
    { debounce: 500, maxWait: 1000 },
);

// Fungsi pencarian data
const searchData = () => updateRoute();

function resetData() {
    router.visit(props.routeLink, {
        preserveState: true,
        data: {},
        preserveScroll: true,
        reset: [],
    });
    props.filtering.forEach((filter) => {
        filter.key = filter.default;
    });
    search.value = '';
    limit.value = props.showingLimit == true ? '10' : '1000000';
}

const formatValue = (item, column) => {
    let value;

    if (column.childChildKey) {
        value = item[column.key]?.[column.childKey]?.[column.childChildKey];
    } else if (column.childKey) {
        value = item[column.key]?.[column.childKey];
    } else {
        value = item[column.key];
    }

    // Handle tipe data
    switch (column.type) {
        case 'currency':
            return value != null
                ? parseFloat(value).toLocaleString('id-ID', {
                      style: 'currency',
                      currency: page.props.auth.company.currency,
                      minimumFractionDigits: 0,
                  })
                : '-';

        case 'number':
            return value != null
                ? parseFloat(value).toLocaleString('id-ID')
                : '-';

        case 'date':
            return value
                ? new Date(value).toLocaleDateString('id-ID', {
                      year: 'numeric',
                      month: 'long',
                      day: 'numeric',
                  })
                : '-';

        case 'datetime':
            return value
                ? new Date(value).toLocaleDateString('id-ID', {
                      year: 'numeric',
                      month: 'long',
                      day: 'numeric',
                  }) +
                      ' ' +
                      new Date(value).toLocaleTimeString('id-ID')
                : '-';

        case 'boolean':
            return value ? '✓' : '✗';

        default:
            return value ?? '-';
    }
};

const isSheetFilterOpen = ref(false);
</script>
