import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // Perhatikan baris di bawah ini, pastikan 'resources/sass/app.scss'
            input: [
                'resources/sass/app.scss', 
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});