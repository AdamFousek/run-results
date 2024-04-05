import "./libs/trix";
import './bootstrap';
import '../css/app.css';
import * as Sentry from "@sentry/vue";

if (import.meta.env.VITE_SENTRY_ENABLE === 'true') {
    Sentry.init({
        dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
        integrations: [
            Sentry.browserTracingIntegration(),
            Sentry.replayIntegration({
                maskAllText: false,
                blockAllMedia: false,
            }),
        ],
        // Performance Monitoring
        tracesSampleRate: 0.01, //  Capture 100% of the transactions
    });
}

import { createSSRApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createI18n } from 'vue-i18n';
import Messages from './lang.js';
import 'chartjs-adapter-moment';
import useRoute from './libs/route.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createSSRApp({ render: () => h(App, props) })
            .use(createI18n( {
                legacy: false,
                locale: props.initialPage.props.locale,
                messages: Messages,
            }))
            .use(plugin)
            .mixin({
                methods: {
                    route: (name, params, absolute, config = Ziggy) => useRoute().route(name, params, absolute, config),
                },
            })
            .mount(el)
            .$nextTick(() => {
                delete el.dataset.page

                const meta = document.createElement('meta')
                meta.name = 'naive-ui-style'
                document.head.appendChild(meta)
            });
    },
    progress: {
        color: '#4B5563',
    },
});
