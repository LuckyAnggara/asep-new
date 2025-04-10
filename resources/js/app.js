import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import { createApp, h } from 'vue';
import { createI18n } from 'vue-i18n';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import en from './locales/en.json';
import id from './locales/id.json';

const pinia = createPinia();
const appName = import.meta.env.VITE_APP_NAME || 'Catat Semua';

const i18n = createI18n({
    legacy: false,
    locale: 'en',
    fallbackLocale: 'id',
    messages: { en, id },
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .component('VueDatePicker', VueDatePicker)
            .use(plugin)
            .use(pinia)
            .use(i18n)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
