import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            // host untuk server
            // host: '10.14.39.147',
            // host untuk local
            host: '127.0.0.1',
            protocol: 'ws'
        },
        watch: {
            usePolling: true
        },
    },
});