import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        }),

        tailwindcss(),
    ],

server: {
    host: '0.0.0.0', // ← tambahkan ini
    cors: true,
    hmr: {
        host: 'localhost',
    },
    allowedHosts: [
        '.ngrok-free.dev',
    ],
},
})