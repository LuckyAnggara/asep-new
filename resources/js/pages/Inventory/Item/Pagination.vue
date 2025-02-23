<script setup>
defineProps({ links: Array });
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

function goTo(url) {
    console.info(url);
    if (url) {
        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <div class="mt-4 flex justify-center space-x-1">
        <div v-for="(link, key) in links" :key="key">
            <Button
                v-if="link.url"
                @click="goTo(link.url)"
                class="h-9 min-w-10 px-2 py-0"
                :variant="link.active ? 'default' : 'outline'"
                v-html="link.label"
            ></Button>
        </div>
    </div>
</template>
