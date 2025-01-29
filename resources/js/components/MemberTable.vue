<script setup>
const props = defineProps({
    members: {
        type: Object,
        required: true,
    },
});
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuPortal,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
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
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { router, WhenVisible } from '@inertiajs/vue3';
import { EllipsisVertical, Eye, Trash2, Search } from 'lucide-vue-next';
import { reactive, watch, ref } from 'vue';
import { refDebounced } from '@vueuse/core';

const limit = ref(route().params.limit?.toString() ?? '10');
const page = ref(route().params.page?.toString() ?? '1');
const active = ref(route().params.active?.toString() ?? 'all');
const gender = ref(route().params.gender?.toString() ?? 'all');
const searchValue = ref(route().params.search?.toString() ?? '');

const updateRoute = (additionalData = {}) => {
    router.visit('members', {
        data: {
            limit: limit.value,
            active: active.value,
            search: searchValue.value,
            gender: gender.value,
            ...additionalData,
        },
    });
};

watch(limit, (newValue) => updateRoute());
watch(active, (newValue) => updateRoute());
watch(gender, (newValue) => updateRoute());

const paginate = (url) => {
    if (url) {
        router.get(url, {
            data: {
                limit: limit.value,
                active: active.value,
                search: searchValue.value,
                gender: gender.value,
            },
        });
    }
};

const toPage = (pageNumber) => updateRoute({ page: pageNumber });
const toFirstPage = () => paginate(props.members.first_page_url);
const toLastPage = () => paginate(props.members.last_page_url);
const toNextPage = () => paginate(props.members.next_page_url);
const toPrevPage = () => paginate(props.members.prev_page_url);

const searchData = () => updateRoute();

const getAvatarImage = (imagePath) =>
    imagePath
        ? `/storage/${imagePath}`
        : 'https://s3.amazonaws.com/37assets/svn/765-default-avatar.png';
</script>

<template>
    <div>
        <div class="mb-4 flex flex-row space-x-6">
            <div class="grid items-center gap-1.5">
                <Label>Show</Label>
                <Select v-model="limit">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Show data" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem value="10"> 10 </SelectItem>
                            <SelectItem value="20"> 20 </SelectItem>
                            <SelectItem value="100"> 100 </SelectItem>
                            <SelectItem value="500"> 500 </SelectItem>
                            <SelectItem value="1000000000"> Semua </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
            <div class="relative grid w-full items-center gap-1.5">
                <Label>Search</Label>
                <div class="relative w-full">
                    <Search
                        class="absolute left-2 top-2.5 size-4 text-muted-foreground"
                    />
                    <Input
                        @keyup.enter="searchData()"
                        v-model="searchValue"
                        placeholder="Search"
                        class="pl-8"
                    />
                </div>
            </div>
            <div class="grid items-center gap-1.5">
                <Label>Gender</Label>
                <Select v-model="gender">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Status Member" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem value="all"> Semua </SelectItem>
                            <SelectItem value="male"> Laki - Laki </SelectItem>
                            <SelectItem value="female"> Perempuan </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
            <div class="grid items-center gap-1.5">
                <Label>Status</Label>
                <Select v-model="active">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Status Member" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem value="all"> Semua </SelectItem>
                            <SelectItem value="true"> Aktif </SelectItem>
                            <SelectItem value="false"> Non Aktif </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
        </div>
        <WhenVisible data="permissions">
            <template #fallback>
                <div class="my-6 w-full text-center">Loading data...</div>
            </template>
            <div class="flex min-h-[600px] flex-col justify-between space-y-6">
                <div
                    class="scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 max-h-[600px] overflow-auto"
                >
                    <Table>
                        <TableHeader>
                            <TableRow class="font-bold uppercase">
                                <TableHead class="w-[50px]"> No </TableHead>
                                <TableHead>ID Member</TableHead>
                                <TableHead>Nama</TableHead>
                                <TableHead>Phone</TableHead>
                                <TableHead>Tanggal Lahir</TableHead>
                                <TableHead>Gender</TableHead>
                                <TableHead>Jabatan</TableHead>
                                <TableHead>Departemen</TableHead>
                                <TableHead>Tanggal bergabung</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(item, index) in members.data"
                                :key="index"
                            >
                                <TableCell class="font-medium">
                                    {{ members.from + index }}
                                </TableCell>
                                <TableCell class="font-medium">
                                    {{ item.member_id }}
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Avatar>
                                            <AvatarImage
                                                :src="
                                                    getAvatarImage(item.photo)
                                                "
                                            />
                                            <AvatarFallback>VC</AvatarFallback>
                                        </Avatar>
                                        {{ item.name }}
                                    </div>
                                </TableCell>
                                <TableCell>{{ item.phone }}</TableCell>
                                <TableCell>{{ item.date_of_birth }}</TableCell>
                                <TableCell>{{ item.gender }}</TableCell>
                                <TableCell>{{ item.job_title }}</TableCell>
                                <TableCell>{{ item.department }}</TableCell>
                                <TableCell>{{ item.join_date }}</TableCell>
                                <TableCell>
                                    {{
                                        item.active == true
                                            ? 'Aktif'
                                            : 'Non Aktif'
                                    }}
                                </TableCell>
                                <TableCell>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <EllipsisVertical
                                                class="cursor-pointer"
                                                :size="16"
                                                :stroke-width="0.5"
                                            />
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent class="w-56">
                                            <DropdownMenuLabel
                                                >Actions</DropdownMenuLabel
                                            >
                                            <DropdownMenuSeparator />
                                            <DropdownMenuGroup>
                                                <DropdownMenuItem
                                                    class="cursor-pointer"
                                                >
                                                    <span>Detail</span>
                                                    <DropdownMenuShortcut
                                                        ><Eye
                                                            :size="16"
                                                            :stroke-width="0.5"
                                                    /></DropdownMenuShortcut>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem
                                                    class="cursor-pointer"
                                                >
                                                    <span>Delete</span>
                                                    <DropdownMenuShortcut
                                                        ><Trash2
                                                            :size="16"
                                                            :stroke-width="0.5"
                                                    /></DropdownMenuShortcut>
                                                </DropdownMenuItem>
                                            </DropdownMenuGroup>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="mt-4 place-self-center justify-self-end">
                    <Pagination
                        v-slot="{ page }"
                        :total="members.total"
                        :itemsPerPage="members.per_page"
                        :sibling-count="1"
                        show-edges
                        :default-page="members.current_page"
                    >
                        <PaginationList
                            v-slot="{ items }"
                            class="flex items-center gap-1"
                        >
                            <PaginationFirst @click="toFirstPage()" />
                            <PaginationPrev @click="toPrevPage()" />
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
                                            item.value === page
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

                            <PaginationNext @click="toNextPage()" />
                            <PaginationLast @click="toLastPage()" />
                        </PaginationList>
                    </Pagination>
                </div>
            </div>
        </WhenVisible>
    </div>
</template>
