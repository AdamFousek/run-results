import { createInertiaApp } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'
import { createI18n } from 'vue-i18n'
import Messages from '@/lang.js'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m.js'
import { Ziggy } from './ziggy';

createServer(page =>
    createInertiaApp({
        page,
        render: renderToString,
        resolve: name => {
            const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
            return pages[`./Pages/${name}.vue`]
        },
        setup({App, props, plugin}) {
            return createSSRApp({
                render: () => h(App, props),
            })
                .use(createI18n({
                    legacy: false,
                    locale: props.initialPage.props.locale,
                    messages: Messages,
                }))
                .use(ZiggyVue, Ziggy)
                .use(plugin)
        },
    }),
)