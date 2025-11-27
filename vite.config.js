import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js'], // ✅ Fix here: Changed [ ] to { }
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js', // ✅ Alias Vue to support template compilation
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true, // ✅ Suppress deprecation warnings
            },
        },
    },
});
