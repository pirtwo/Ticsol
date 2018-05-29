import Vue from 'vue';
import Vuex from 'vuex';
import { localStorage } from '../storage';

import { authModule } from './modules/auth';
import { userModule } from './modules/user';
import { loadingModule } from './modules/loading';
import { sidebarModule } from './modules/sidebar';

Vue.use(Vuex);

export const store = new Vuex.Store({    
    modules: {
        auth: authModule,
        user: userModule,
        loading: loadingModule,
        sidebar: sidebarModule,        
    },
    plugins: [localStorage.plugin],
});