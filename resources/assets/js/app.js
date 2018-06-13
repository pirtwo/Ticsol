
require('./bootstrap');

import Vue from 'vue';
import { sync } from 'vuex-router-sync';
import { store } from './store/store.js';
import { router } from './router.js';
import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.min.css';
import 'vue-material/dist/theme/black-green-light.css';

import App from "./components/App.vue";

Vue.use(VueMaterial)

sync(store, router);

const app = new Vue({
    el: '#app',
    store,
    router,
    components: { App },
});
