<script setup>
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { vAutoAnimate } from '@formkit/auto-animate/vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';

import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Separator } from '@/components/ui/separator';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarInset,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarProvider,
    SidebarRail,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import {
    AudioWaveform,
    BadgeCheck,
    Bell,
    Bot,
    BookOpen,
    ChevronRight,
    ChevronsUpDown,
    Command,
    CreditCard,
    Folder,
    Forward,
    Frame,
    GalleryVerticalEnd,
    LogOut,
    Map,
    MoreHorizontal,
    PieChart,
    Plus,
    Settings2,
    Sparkles,
    SquareTerminal,
    Trash2,
    BookOpenCheck,
    UserCircle,
} from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';
const page = usePage();

// This is sample data.
const data = computed(() => {
    return {
        user: {
            name: 'shadcn',
            email: 'm@example.com',
            avatar: 'https://s3.amazonaws.com/37assets/svn/765-default-avatar.png',
        },
        company: {
            name: 'Acme Inc',
            logo: GalleryVerticalEnd,
            plan: 'Enterprise',
        },
        navMain: [
            {
                title: 'Dashboard',
                url: 'dashboard',
                icon: SquareTerminal,
                status: page.url.startsWith('/dashboard') ? true : false,
            },
            {
                title: 'Accounting',
                icon: BookOpenCheck,
                status: page.url.startsWith('/chart-of-accounts')
                    ? true
                    : false,

                items: [
                    {
                        title: 'Journal',
                        url: 'chart-of-accounts.index',
                    },
                    {
                        title: 'Ledger',
                        url: 'chart-of-accounts.index',
                    },
                    {
                        title: 'Balance Sheet',
                        url: 'chart-of-accounts.index',
                    },
                    {
                        title: 'Cash Flow',
                        url: 'chart-of-accounts.index',
                    },
                    {
                        title: 'Account (COA)',
                        url: 'chart-of-accounts.index',
                    },
                ],
            },
            {
                title: 'Member',
                icon: UserCircle,
                status: page.url.startsWith('/members') ? true : false,

                items: [
                    {
                        title: 'Data',
                        url: 'members.index',
                    },
                    {
                        title: 'Baru',
                        url: 'members.create',
                    },
                ],
            },
        ],
    };
});
import { Link, router, usePage } from '@inertiajs/vue3';

console.info(data.value);
console.info(page.url);
</script>

<template>
    <SidebarProvider>
        <Sidebar collapsible="icon">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <div class="my-2 flex space-x-2 p-1">
                            <div
                                class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg"
                            >
                                <component
                                    :is="data.company.logo"
                                    class="size-4"
                                />
                            </div>
                            <div
                                class="grid flex-1 text-left text-sm leading-tight"
                            >
                                <span class="truncate font-semibold">{{
                                    data.company.name
                                }}</span>
                                <span class="truncate text-xs">{{
                                    data.company.plan
                                }}</span>
                            </div>
                        </div>
                        <Separator />
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                <SidebarGroup>
                    <SidebarGroupLabel>Menu</SidebarGroupLabel>
                    <SidebarMenu>
                        <Collapsible
                            v-for="item in data.navMain"
                            :key="item.title"
                            as-child
                            v-model.open="item.status"
                            class="group/collapsible"
                        >
                            <SidebarMenuItem>
                                <CollapsibleTrigger as-child>
                                    <SidebarMenuButton :tooltip="item.title">
                                        <component :is="item.icon" />
                                        <span v-if="item.items">{{
                                            item.title
                                        }}</span>
                                        <Link
                                            :href="route(item.url)"
                                            v-else
                                            preserve-state
                                        >
                                            {{ item.title }}
                                        </Link>
                                        <ChevronRight
                                            v-if="item.items"
                                            class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                        />
                                    </SidebarMenuButton>
                                </CollapsibleTrigger>
                                <CollapsibleContent v-if="item.items">
                                    <SidebarMenuSub>
                                        <SidebarMenuSubItem
                                            v-for="subItem in item.items"
                                            :key="subItem.title"
                                        >
                                            <SidebarMenuSubButton as-child>
                                                <Link
                                                    :href="route(subItem.url)"
                                                    preserve-state
                                                >
                                                    <span>{{
                                                        subItem.title
                                                    }}</span>
                                                </Link>
                                            </SidebarMenuSubButton>
                                        </SidebarMenuSubItem>
                                    </SidebarMenuSub>
                                </CollapsibleContent>
                            </SidebarMenuItem>
                        </Collapsible>
                    </SidebarMenu>
                </SidebarGroup>
            </SidebarContent>
            <SidebarFooter>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <SidebarMenuButton
                                    size="lg"
                                    class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                                >
                                    <Avatar class="h-8 w-8 rounded-lg">
                                        <AvatarImage
                                            :src="data.user.avatar"
                                            :alt="data.user.name"
                                        />
                                        <AvatarFallback class="rounded-lg">
                                            CN
                                        </AvatarFallback>
                                    </Avatar>
                                    <div
                                        class="grid flex-1 text-left text-sm leading-tight"
                                    >
                                        <span class="truncate font-semibold">{{
                                            data.user.name
                                        }}</span>
                                        <span class="truncate text-xs">{{
                                            data.user.email
                                        }}</span>
                                    </div>
                                    <ChevronsUpDown class="ml-auto size-4" />
                                </SidebarMenuButton>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent
                                class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                                side="bottom"
                                align="end"
                                :side-offset="4"
                            >
                                <DropdownMenuLabel class="p-0 font-normal">
                                    <div
                                        class="flex items-center gap-2 px-1 py-1.5 text-left text-sm"
                                    >
                                        <Avatar class="h-8 w-8 rounded-lg">
                                            <AvatarImage
                                                :src="data.user.avatar"
                                                :alt="data.user.name"
                                            />
                                            <AvatarFallback class="rounded-lg">
                                                CN
                                            </AvatarFallback>
                                        </Avatar>
                                        <div
                                            class="grid flex-1 text-left text-sm leading-tight"
                                        >
                                            <span
                                                class="truncate font-semibold"
                                                >{{ data.user.name }}</span
                                            >
                                            <span class="truncate text-xs">{{
                                                data.user.email
                                            }}</span>
                                        </div>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />

                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem>
                                        <BadgeCheck />
                                        Account
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Settings2 />
                                        Setting
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem>
                                    <LogOut />
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="a"
                                    >
                                        Log Out
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarFooter>
            <SidebarRail />
        </Sidebar>
        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12"
            >
                <div class="flex items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />
                    <Separator orientation="vertical" class="mr-2 h-4" />
                    <Breadcrumb>
                        <BreadcrumbList>
                            <BreadcrumbItem class="hidden md:block">
                                <BreadcrumbLink href="#">
                                    Building Your Application
                                </BreadcrumbLink>
                            </BreadcrumbItem>
                            <BreadcrumbSeparator class="hidden md:block" />
                            <BreadcrumbItem>
                                <BreadcrumbPage>Data Fetching</BreadcrumbPage>
                            </BreadcrumbItem>
                        </BreadcrumbList>
                    </Breadcrumb>
                </div>
            </header>
            <main
                class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6"
                v-auto-animate
            >
                <!-- Page Content -->
                <Toaster />
                <slot />
            </main>
        </SidebarInset>
    </SidebarProvider>
</template>
