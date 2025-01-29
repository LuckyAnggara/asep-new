<script setup>
import { Badge } from '@/components/ui/badge';

import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import {
    Bell,
    CircleUser,
    Home,
    LineChart,
    Menu,
    Package,
    Package2,
    Search,
    ShoppingCart,
    Users,
} from 'lucide-vue-next';

import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Inventory</h1>
        </div>
        <div
            class="flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
        >
            <div class="flex flex-col items-center gap-1 text-center">
                <h3 class="text-2xl font-bold tracking-tight">
                    You have no products
                </h3>
                <p class="text-sm text-muted-foreground">
                    You can start selling as soon as you add a product.
                </p>
                <Button class="mt-4"> Add Product </Button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
