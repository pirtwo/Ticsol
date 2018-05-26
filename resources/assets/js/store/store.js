import Vue from 'vue';
import Vuex from 'vuex';
import { localStorage } from '../storage';
import { userModule } from './modules/user.js';
import {
    APP_LOADING,
    APP_LOADING_MESSAGE
} from './mutation-types';
import { sidebarModule } from './modules/sidebar';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        loading: false,
        loadingMessage: '',
    },
    getters: {
        isLoading: (state) => {
            return state.loading;
        },
        loadingMessage: (state) => {
            return state.loadingMessage;
        }
    },
    mutations: {
        [APP_LOADING](state, { isLoading }) {                   
            state.loading = isLoading;
            //console.log(state.loading);     
        },
        [APP_LOADING_MESSAGE](state, { message }) {
            state.loadingMessage = message;
            //console.log(state.loadingMessage);
        }
    },
    actions: {
        loading: ({ state, commit }, { isLoading, message }) => {            
            commit(APP_LOADING, {isLoading});
            commit(APP_LOADING_MESSAGE, {message});
        }
    },
    modules: {
        user: userModule,
        sidebar: sidebarModule
    },
    plugins: [localStorage.plugin],
});