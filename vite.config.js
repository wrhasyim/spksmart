import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Mengizinkan akses dari jaringan lokal
        hmr: {
            host: '192.168.0.124', // <--- Ganti dengan IP Address PC Utama kamu
        },
    },
    // server: {
    //     watch: {
    //         ignored: ['**/storage/framework/views/**'],
    //     },
    // },
});
