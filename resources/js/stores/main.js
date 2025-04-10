import { usePage } from '@inertiajs/vue3';
import { GalleryVerticalEnd } from 'lucide-vue-next';
import { defineStore } from 'pinia';
const page = usePage();

export const useMainStore = defineStore('main', {
    state: () => ({
        isLoading: false,
        tahunOptions: [
            {
                value: '2024',
                label: '2024',
            },
            {
                value: '2025',
                label: '2025',
            },
            {
                value: '2026',
                label: '2026',
            },
            {
                value: '2027',
                label: '2027',
            },
            {
                value: '2028',
                label: '2028',
            },
        ],
        bulanOptions: [
            {
                value: '01',
                label: 'Januari',
            },
            {
                value: '02',
                label: 'Februari',
            },
            {
                value: '03',
                label: 'Maret',
            },
            {
                value: '04',
                label: 'April',
            },
            {
                value: '05',
                label: 'Mei',
            },
            {
                value: '06',
                label: 'Juni',
            },
            {
                value: '07',
                label: 'Juli',
            },
            {
                value: '08',
                label: 'Agustus',
            },
            {
                value: '09',
                label: 'September',
            },
            {
                value: '10',
                label: 'Oktober',
            },
            {
                value: '11',
                label: 'November',
            },
            {
                value: '12',
                label: 'Desember',
            },
        ],
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
        activeUrl: '',
        active: false,
    }),
    getters: {
        url(state) {
            return page.url;
        },
    },
    actions: {
        async getProvinsi(page = '') {
            this.isLoading = true;
            try {
                const response = await axiosIns.get(`/api/provinsi`);
                this.provinsiOptions = response.data.data;
            } catch (error) {
                alert(error.message);
            } finally {
                this.isLoading = false;
            }
            return false;
        },
    },
});
