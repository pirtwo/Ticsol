
require('./bootstrap');

import Vue from 'vue';
import { sync } from 'vuex-router-sync';
import { store } from './store/store.js';
import { router } from './router.js';
import App from "./components/App.vue";

/* plugins */
import formFeedback from './plugin/formFeedback-plugin.js';
Vue.use(formFeedback);

sync(store, router);

new Vue({
    el: '#app',
    store,
    router,
    components: { App },
});
