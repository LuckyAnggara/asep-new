import { usePage } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';
import { computed } from 'vue';

const page = usePage();
const company = computed(() => page.props.auth.company);

export function cn(...inputs) {
    return twMerge(clsx(inputs));
}

export const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: company.value.currency,
    }).format(value);
};
