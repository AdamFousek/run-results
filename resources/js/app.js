import './bootstrap';
import '../css/app.css';
import Trix from 'trix';
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
        tracesSampleRate: 1.0, //  Capture 100% of the transactions
        // Set 'tracePropagationTargets' to control for which URLs distributed tracing should be enabled
        tracePropagationTargets: ["localhost", /^https:\/\/zavodymimodrahu\.cz/],
        // Session Replay
        replaysSessionSampleRate: 0.1, // This sets the sample rate at 10%. You may want to change it to 100% while in development and then sample at a lower rate in production.
        replaysOnErrorSampleRate: 1.0, // If you're not already sampling the entire session, change the sample rate to 100% when sampling sessions where errors occur.
    });
}


window.Trix = Trix;

import { createSSRApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createI18n } from 'vue-i18n';
import Messages from './lang.js';
import { Ziggy } from './ziggy';
import 'chartjs-adapter-moment';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const meta = document.createElement('meta')
meta.name = 'naive-ui-style'
document.head.appendChild(meta)

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
