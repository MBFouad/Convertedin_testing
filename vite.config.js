import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/vendor/chosen/bootstrap-chosen.css',
                'resources/vendor/jasny/jasny-bootstrap.min.css',
                'resources/vendor/switchery/switchery.css',
                'resources/vendor/summernote/summernote.css',
                'resources/vendor/summernote/summernote-bs4.min.css',
                'resources/scss/app.scss',
                'resources/scss/bootstrap5.scss',
                'resources/scss/device-map.scss',
                'resources/scss/service-manuals.scss',
                'resources/scss/customers.scss',
                'resources/scss/distributions.scss',
                'resources/js/test.js',
                'resources/js/main.js',
                // 'resources/js/custom.js',
                'resources/js/customers.js',
                'resources/js/app.js',
                'resources/js/bus-data.js',
                'resources/js/address-handler.js',
                'resources/js/chosen.js',
                'resources/js/device-map.js',
                'resources/vendor/jasny/jasny-bootstrap.min.js',
                'resources/vendor/summernote/summernote.min.js',
                // 'resources/vendor/switchery/switchery.js',
                'resources/js/tech-data.js',
                'resources/vendor/iCheck/icheck.min.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~leaflet': path.resolve(__dirname, 'node_modules/leaflet'),
            '~chosen': path.resolve(__dirname, 'node_modules/chosen-js'),
        }
    }
});
