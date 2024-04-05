import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 1500,
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                compilerOptions: {
                    isCustomElement: (tag) => ['trix-editor'].includes(tag),
                }
            },
        }),
    ],
    ssr: {
        noExternal: ['naive-ui', 'vueuc', 'css-render', 'date-fns'],
    },
    resolve: {
        alias: {
            '@': '/resources/js/',
            ziggy: path.resolve('vendor/tightenco/ziggy/src/js/index.js'),
        },
    }
});
