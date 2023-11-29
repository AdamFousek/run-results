import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createI18n } from 'vue-i18n';
import Messages from './lang.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const meta = document.createElement('meta')
meta.name = 'naive-ui-style'
document.head.appendChild(meta)

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(createI18n( {
                legacy: false,
                locale: props.initialPage.props.locale,
                messages: Messages,
            }))
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el)
            .$nextTick(() => {
                delete el.dataset.page
            });
    },
    progress: {
        color: '#4B5563',
    },
});
