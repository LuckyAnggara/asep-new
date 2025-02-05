<script setup>
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

defineProps({
    account: Object,
    level: { type: Number, default: 0 },
});

const isExpanded = ref(false);
const toggleVisibility = () => (isExpanded.value = !isExpanded.value);

// Fungsi format rupiah
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(value);
};
</script>

<template>
    <div>
        <!-- Row Akun -->
        <div
            class="flex items-center justify-between border-b bg-gray-50 p-4 transition hover:bg-gray-100"
            :class="{
                'pl-4': level === 1,
                'pl-8': level === 2,
                'pl-12': level === 3,
            }"
        >
            <div class="flex items-center space-x-2">
                <Button
                    v-if="account.sub_category || account.coa"
                    @click="toggleVisibility"
                    variant="outline"
                    size="sm"
                >
                    {{ isExpanded ? '−' : '+' }}
                </Button>
                <span class="font-semibold">{{ account.name }}</span>
            </div>
            <span class="font-mono text-sm text-gray-700"
                >Rp {{ formatCurrency(account.total_balance) }}</span
            >
        </div>

        <!-- Sub Kategori -->
        <div v-if="isExpanded">
            <AccountItem
                v-for="sub in account.sub_category"
                :key="sub.id"
                :account="sub"
                :level="level + 1"
            />
            <AccountItem
                v-for="coa in account.coa"
                :key="coa.id"
                :account="coa"
                :level="level + 1"
            />
        </div>
    </div>
</template>
