import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: true,     
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'YOUR_NGROK_DOMAIN.ngrok-free.dev'
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
