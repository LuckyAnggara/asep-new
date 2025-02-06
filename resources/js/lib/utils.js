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
        minimumFractionDigits: company.value.decimal,
    }).format(value);
};

export const formatValue = (value, type) => {
    // Handle tipe data
    switch (type) {
        case 'currency':
            return value != null
                ? parseFloat(value).toLocaleString('id-ID', {
                      style: 'currency',
                      currency: page.props.auth.company.currency,
                      minimumFractionDigits: 0,
                  })
                : '-';

        case 'number':
            return value != null ? parseFloat(value).toLocaleString('id-ID') : '-';

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
