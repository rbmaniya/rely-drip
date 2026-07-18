import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/js/app.js',
                'resources/css/storefront.scss',
                'resources/js/storefront.js',
            ],
            refresh: true,
        }),
    ],
});
