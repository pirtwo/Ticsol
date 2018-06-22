import Vue from 'vue';
import Vuex from 'vuex';
import { localStorage } from '../storage';

import { authModule } from './modules/auth';
import { userModule } from './modules/app-user';
import { loadingModule } from './modules/loading';
import { sidebarModule } from './modules/sidebar';
import { jobModule } from './modules/res-job';
import { scheduleModule } from './modules/res-schedule';

Vue.use(Vuex);

export const store = new Vuex.Store({    
    modules: {
        auth: authModule,
        user: userModule,
        loading: loadingModule,
        sidebar: sidebarModule,
        schedule: scheduleModule,
        job: jobModule,         
    },
    plugins: [localStorage.plugin],
});