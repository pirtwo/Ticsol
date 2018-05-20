
require('./bootstrap');

import Vue from 'vue';
import { store } from './store/store.js';
import { router } from './router.js';
import App from "./components/App.vue";

const app = new Vue({
    el: '#app',
    store,
    router,
    components: { App },
});
