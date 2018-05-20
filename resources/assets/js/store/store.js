import Vue from 'vue';
import Vuex from 'vuex';
import {userModule} from './modules/user.js'

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules:{
        user: userModule,
    }
});