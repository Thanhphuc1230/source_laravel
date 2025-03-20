import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // admin css
                'resources/css/admin/bootstrap.min.css',
                'resources/css/admin/icons.min.css',
                'resources/css/admin/app.min.css',
                'resources/css/admin/custom.min.css',
                'resources/js/lord-icon-2.1.0.js',
                'resources/css/admin/custom-fixes.css',
                // admin js
                'resources/js/admin/layout.js',
                'resources/js/admin/plugins.js',
                'resources/js/admin/app.js',
                // lib js
                'resources/js/bootstrap/js/bootstrap.bundle.min.js',
                'resources/js/simplebar/simplebar.min.js',
                'resources/js/node-waves/waves.min.js',
                'resources/js/simplebar/feather.min.js',
                'resources/js/list.js/list.min.js',
                'resources/js/list.pagination.js/list.pagination.min.js',
                'resources/js/prismjs/prism.js',
                'resources/js/listjs.init.js',
            ],
            refresh: true,
        }),
    ],
    // Thêm cấu hình xử lý fonts
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.ttf') || 
                        assetInfo.name.endsWith('.woff') || 
                        assetInfo.name.endsWith('.woff2') ||
                        assetInfo.name.endsWith('.eot')) {
                        return 'css/fonts/[name][extname]';
                    }
                    if (assetInfo.name.endsWith('.png') || 
                        assetInfo.name.endsWith('.jpg') || 
                        assetInfo.name.endsWith('.jpeg') ||
                        assetInfo.name.endsWith('.gif') ||
                        assetInfo.name.endsWith('.svg')) {
                        return 'images/admin/[name][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
    // Cấu hình để Vite có thể tìm thấy fonts
    resolve: {
        alias: {
            '@fonts': '/resources/css/fonts',
            '@images': '/resources/images',
            '@admin': '/resources/admin',
        },
    },
    // Cấu hình public directory
    publicDir: 'public',
});
