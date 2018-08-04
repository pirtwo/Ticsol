import Vue from 'vue';
import Vuex from 'vuex';
import { localStorage } from '../storage';

import { coreModule } from './modules/core';
import { userModule } from './modules/user';
import { resourceModule} from './modules/resource';

Vue.use(Vuex);

export const store = new Vuex.Store({    
    modules: {        
        core: coreModule,
        user: userModule,
        resource: resourceModule
    },
    plugins: [localStorage.plugin],
});