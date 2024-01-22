import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/home.scss',
                'resources/sass/about.scss',
                'resources/sass/_variables.scss',
                'resources/sass/account.scss',
                'resources/sass/cart.scss',
                'resources/sass/appointment.scss',
                'resources/sass/checkout.scss',
                'resources/sass/product.scss',
                'resources/sass/services.scss',
                'resources/sass/shop.scss',
		        'resources/sass/privacy_policy.scss',

                'resources/views/dashboard/sass/app.scss',
                'resources/views/dashboard/sass/appointment.scss',
                'resources/views/dashboard/sass/categories.scss',
                'resources/views/dashboard/sass/index.scss',
                'resources/views/dashboard/sass/orders.scss',
                'resources/views/dashboard/sass/payment.scss',
                'resources/views/dashboard/sass/products.scss',
                'resources/views/dashboard/sass/subcategories.scss',
                'resources/views/dashboard/sass/user.scss',
                'resources/views/dashboard/sass/variable.scss',
                'resources/views/dashboard/sass/vendor.scss',
                'resources/views/dashboard/sass/blogs.scss',

                'resources/js/app.js',
                'resources/js/custom.js',
                'resources/js/products.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
