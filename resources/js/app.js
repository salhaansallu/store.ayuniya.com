/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

import toastr from 'toastr';
window.toastr = toastr;

toastr.options = {
    timeOut: 4000,
    progressBar: true,
}

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});
const md_searchbar = createApp({});
const productBtn = createApp({});
const varient = createApp({});
const cart_checkoutBtn = createApp({});
const checkoutBtn = createApp({});

import pcsearch from './components/search.vue';
app.component('pc-search', pcsearch);

import mdsearch from './components/md_search.vue';
md_searchbar.component('md-search', mdsearch);

import product_btns from './components/productBtn.vue';
productBtn.component('product-btn', product_btns);

import pro_varient from './components/variant.vue';
varient.component('pro-varient', pro_varient);

import cart_checkout_btn from './components/cart_checkout.vue';
cart_checkoutBtn.component('cartcheckout-btn', cart_checkout_btn);

import checkout_btn from './components/checkout.vue';
checkoutBtn.component('checkout-btn', checkout_btn);



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#searchbar');
md_searchbar.mount('#mobile-searchbox');
productBtn.mount('#product_action_btns');
varient.mount('#varient');
cart_checkoutBtn.mount('#cartcheckout_btn');
checkoutBtn.mount('#checkout_btn');

