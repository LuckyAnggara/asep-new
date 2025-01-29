<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import Toaster from '@/components/ui/toast/Toaster.vue';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-white dark:bg-gray-900"
    >
        <Toaster />

        <slot />
    </div>
</template>
