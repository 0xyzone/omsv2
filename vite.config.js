import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/mukhiya/theme.css', 'resources/css/filament/taker/theme.css', 'resources/css/filament/maker/theme.css'],
            refresh: [
                "app/Livewire/**",
                "app/Filament/**",
                "app/Providers/**",
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
