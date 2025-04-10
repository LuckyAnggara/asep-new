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
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { router, WhenVisible } from '@inertiajs/vue3';
import { EllipsisVertical, Eye, Trash2, Search } from 'lucide-vue-next';
import { reactive, watch, ref } from 'vue';
import { refDebounced } from '@vueuse/core';
import Pagination from '@/Components/Pagination.vue';

const limit = ref(route().params.limit?.toString() ?? '10');
const page = ref(route().params.page?.toString() ?? '1');
const active = ref(route().params.active?.toString() ?? 'all');
const gender = ref(route().params.gender?.toString() ?? 'all');
const searchValue = ref(route().params.search?.toString() ?? '');

const actions = ref([
    {
        label: 'Detail',
        handler: (item) => {
            router.get('/inventory/item/' + item.id);
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

const updateRoute = (additionalData = {}) => {
    router.visit('members', {
        data: {
            limit: limit.value,
            active: active.value,
            search: searchValue.value,
            gender: gender.value,
            ...additionalData,
        },
        preserveState: true,
        preserveScroll: true,
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

const getAvatarImage = (imagePath) => (imagePath ? `/storage/${imagePath}` : 'https://s3.amazonaws.com/37assets/svn/765-default-avatar.png');
</script>

<template>
    <div>
        <div class="mb-4 flex flex-row space-x-6">
            <div class="grid items-center gap-1.5">
                <Label>{{ $t('member.table.show') }}</Label>
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
                            <SelectItem value="1000000000"> {{ $t('member.filters.all') }} </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
            <div class="relative grid w-full items-center gap-1.5">
                <Label> {{ $t('member.table.search') }}</Label>
                <div class="relative w-full">
                    <Search class="absolute left-2 top-2.5 size-4 text-muted-foreground" />
                    <Input @keyup.enter="searchData()" v-model="searchValue" :placeholder="$t('member.table.search')" class="pl-8" />
                </div>
            </div>
            <div class="grid items-center gap-1.5">
                <Label> {{ $t('member.table.gender') }}</Label>
                <Select v-model="gender">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Status Member" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem value="all"> {{ $t('member.filters.all') }} </SelectItem>
                            <SelectItem value="male"> {{ $t('member.filters.male') }} </SelectItem>
                            <SelectItem value="female"> {{ $t('member.filters.female') }} </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
            <div class="grid items-center gap-1.5">
                <Label>{{ $t('member.table.status') }}</Label>
                <Select v-model="active">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue :placeholder="$t('member.table.member_status')" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem value="all"> {{ $t('member.filters.all') }} </SelectItem>
                            <SelectItem value="true"> {{ $t('member.filters.active') }} </SelectItem>
                            <SelectItem value="false"> {{ $t('member.filters.inactive') }} </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
        </div>
        <WhenVisible data="permissions">
            <template #fallback>
                <div class="my-6 w-full text-center">{{ $t('member.please_wait') }}</div>
            </template>
            <div class="flex flex-col justify-between space-y-6">
                <div class="scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 overflow-auto">
                    <Table>
                        <TableHeader>
                            <TableRow class="font-bold uppercase">
                                <TableHead class="w-[50px]"> # </TableHead>
                                <TableHead>{{ $t('member.id_member') }}</TableHead>
                                <TableHead>{{ $t('member.name') }}</TableHead>
                                <TableHead>{{ $t('member.phone') }}</TableHead>
                                <TableHead>{{ $t('member.date_of_birth') }}</TableHead>
                                <TableHead>{{ $t('member.gender') }}</TableHead>
                                <TableHead>{{ $t('member.job_title') }}</TableHead>
                                <TableHead>{{ $t('member.department') }}</TableHead>
                                <TableHead>{{ $t('member.join_date') }}</TableHead>
                                <TableHead>{{ $t('member.active_status') }}</TableHead>
                                <TableHead></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, index) in members.data" :key="index">
                                <TableCell class="font-medium">
                                    {{ members.from + index }}
                                </TableCell>
                                <TableCell class="font-medium">
                                    {{ item.member_id }}
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Avatar>
                                            <AvatarImage :src="getAvatarImage(item.photo)" />
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
                                    {{ item.active == true ? 'Aktif' : 'Non Aktif' }}
                                </TableCell>
                                <TableCell>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <EllipsisVertical class="cursor-pointer" :size="16" :stroke-width="0.5" />
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent class="w-56">
                                            <DropdownMenuLabel>{{ $t('member.table.actions') }}</DropdownMenuLabel>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuGroup>
                                                <DropdownMenuItem
                                                    class="cursor-pointer"
                                                    v-for="(action, actions) in actions"
                                                    :key="actions"
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
                </div>
                <Pagination :links="members.links" />
            </div>
        </WhenVisible>
    </div>
</template>
