import { createInertiaApp } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'
import { createI18n } from 'vue-i18n'
import Messages from '@/lang.js'
import ziggyRoute from 'ziggy';

createServer(page =>
    createInertiaApp({
        page,
        render: renderToString,
        resolve: name => {
            const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
            return pages[`./Pages/${name}.vue`]
        },
        setup({App, props, plugin}) {
                const Ziggy = {
                    ...props.initialPage.props.ziggy,
                    location: new URL(props.initialPage.props.ziggy.location)
                }

                return createSSRApp({
                    render: () => h(App, props),
                })
            .use(createI18n({
                legacy: false,
                locale: props.initialPage.props.locale,
                messages: Messages,
            }))
            .use(plugin)
            .mixin({
                methods: {
                    route: (name, params, absolute, config = Ziggy) => ziggyRoute(name, params, absolute, config),
                },
            })
        },
    }),
)