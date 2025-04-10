<script setup>
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { vAutoAnimate } from '@formkit/auto-animate/vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useMainStore } from '@/stores/main';
// import Toaster from '@/components/ui/toast/Toaster.vue';
import { Toaster } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Icon } from '@iconify/vue';
import { useColorMode } from '@vueuse/core';
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList, BreadcrumbPage, BreadcrumbSeparator } from '@/components/ui/breadcrumb';

import { Collapsible, CollapsibleContent } from '@/components/ui/collapsible';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
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
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarProvider,
    SidebarRail,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import { BadgeCheck, ChevronRight, ChevronsUpDown, GalleryVerticalEnd, LogOut, Settings2 } from 'lucide-vue-next';
import { computed } from 'vue';
import getMenus from '@/lib/menus';
import { ref } from 'vue';

const mode = useColorMode();
const mainStore = useMainStore();
const menus = ref(getMenus());
const page = usePage();
const company = computed(() => page.props.auth.company);
// This is sample data.
const data = computed(() => {
    return {
        user: {
            avatar: 'https://s3.amazonaws.com/37assets/svn/765-default-avatar.png',
            ...page.props.auth.user,
        },
    };
});

// const menus = computed(() => mainStore.menus);

const getLogoImage = () => (company.value.logo ? `/storage/${company.value.logo}` : '');

function goTo(url) {
    router.visit(route(url), {
        preserveState: true,
    });
}

function active(item) {
    item.status = !item.status;
}
</script>

<template>
    <SidebarProvider>
        <Sidebar collapsible="icon">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <div
                            class="my-2 flex flex-row justify-between p-1 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:flex-col"
                        >
                            <div class="flex space-x-2">
                                <div
                                    class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg"
                                >
                                    <img :src="getLogoImage()" />
                                    <!-- <component
                                        :is="data.company.logo"
                                        class="size-4"
                                    /> -->
                                </div>
                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ company.name }}</span>
                                    <span class="truncate text-xs">{{ company.slogan }}</span>
                                </div>
                            </div>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="ghost">
                                        <Icon
                                            icon="radix-icons:moon"
                                            class="h-[1.2rem] w-[1.2rem] rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"
                                        />
                                        <Icon
                                            icon="radix-icons:sun"
                                            class="absolute h-[1.2rem] w-[1.2rem] rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100"
                                        />
                                        <span class="sr-only">Toggle theme</span>
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem @click="mode = 'light'"> Light </DropdownMenuItem>
                                    <DropdownMenuItem @click="mode = 'dark'"> Dark </DropdownMenuItem>
                                    <DropdownMenuItem @click="mode = 'auto'"> System </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
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
                            @click="active(item)"
                            v-for="(item, index) in menus"
                            :key="index"
                            as-child
                            class="group/collapsible"
                            v-model:open="item.status"
                        >
                            <SidebarMenuItem>
                                <SidebarMenuButton :tooltip="item.title">
                                    <component :is="item.icon" />
                                    <span v-if="item.items">{{ item.title }}</span>
                                    <button type="button" @click="goTo(item.url)" v-else>
                                        {{ item.title }}
                                    </button>

                                    <ChevronRight
                                        v-if="item.items"
                                        class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                    />
                                </SidebarMenuButton>
                                <CollapsibleContent v-if="item.items">
                                    <SidebarMenuSub>
                                        <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title">
                                            <SidebarMenuSubButton as-child>
                                                <button type="button" @click="goTo(subItem.url)">
                                                    {{ subItem.title }}
                                                </button>
                                                <!-- <Link
                                                    :href="route(subItem.url)"
                                                    preserve-state
                                                >
                                                    <span>{{
                                                        subItem.title
                                                    }}</span>
                                                </Link> -->
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
                                <SidebarMenuButton size="lg" class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                                    <Avatar
                                        class="h-8 w-8 rounded-lg transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-4 group-has-[[data-collapsible=icon]]/sidebar-wrapper:w-4"
                                    >
                                        <AvatarImage :src="data.user.avatar" :alt="data.user.name" />
                                        <AvatarFallback class="rounded-lg"> CN </AvatarFallback>
                                    </Avatar>
                                    <div class="grid flex-1 text-left text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ data.user.name }}</span>
                                        <span class="truncate text-xs">{{ data.user.email }}</span>
                                    </div>
                                    <ChevronsUpDown class="ml-auto size-4" />
                                </SidebarMenuButton>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg" side="bottom" align="end" :side-offset="4">
                                <DropdownMenuLabel class="p-0 font-normal">
                                    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                        <Avatar class="h-8 w-8 rounded-lg">
                                            <AvatarImage :src="data.user.avatar" :alt="data.user.name" />
                                            <AvatarFallback class="rounded-lg"> CN </AvatarFallback>
                                        </Avatar>
                                        <div class="grid flex-1 text-left text-sm leading-tight">
                                            <span class="truncate font-semibold">{{ data.user.name }}</span>
                                            <span class="truncate text-xs">{{ data.user.email }}</span>
                                        </div>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />

                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem>
                                        <BadgeCheck />
                                        <Link :href="route('profile.edit')"> Account </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Settings2 />
                                        Setting
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem>
                                    <LogOut />
                                    <Link :href="route('logout')" method="post" as="a"> Log Out </Link>
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
                                <BreadcrumbLink href="#"> Building Your Application </BreadcrumbLink>
                            </BreadcrumbItem>
                            <BreadcrumbSeparator class="hidden md:block" />
                            <BreadcrumbItem>
                                <BreadcrumbPage>Data Fetching</BreadcrumbPage>
                            </BreadcrumbItem>
                        </BreadcrumbList>
                    </Breadcrumb>
                </div>
            </header>
            <main class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6" v-auto-animate>
                <!-- Page Content -->

                <Toaster position="bottom-center" richColors />
                <slot />
            </main>
        </SidebarInset>
    </SidebarProvider>
</template>
