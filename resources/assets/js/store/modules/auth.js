import {
    AUTH_TOKEN,
    AUTH_FAILED,
    AUTH_SUCCESS,
    AUTH_LOGOUT,
} from '../mutation-types';
import { auth } from '../../api/http';

export const authModule = {
    namespaced: true,
    state: {
        isAuth: false,
        token: {
            value: '',
            expires_in: '',
            scope: '',
            issued: '',
        }
    },
    getters: {
        getIsAuth: (state) => {
            return state.isAuth;
        },
        getToken: (state) => {
            return state.token;
        },
        getTokenValue: (state) => {
            return state.token.value;
        },
        getTokenExpire: (state) => {
            return state.token.expires_in;
        },
        getTokenScope: (state) => {
            return state.token.scope;
        },
        getTokenIssue: (state) => {
            return state.token.issued;
        }
    },
    mutations: {
        [AUTH_TOKEN](state, payload) {
            state.token.value = payload.access_token;
            state.token.scope = payload.scope;
            state.token.expires_in = payload.expires_in;
            state.token.issued = Date.now();
        },
        [AUTH_FAILED](state) {
            state.isAuth = false;
        },
        [AUTH_SUCCESS](state) {
            state.isAuth = true;
        },
        [AUTH_LOGOUT](state){
            state.token.value = '';
            state.token.scope = '';
            state.token.issued = '';
            state.token.expires_in = '';            
            state.isAuth = false;
        }
    },
    actions: {

        login({ state, commit }, payload) {
            return new Promise((resolve, reject) => {
                auth.login(payload)
                    .then(respond => {
                        if (respond.status === 200) {
                            commit(AUTH_TOKEN, respond.data);
                            commit(AUTH_SUCCESS);                            
                            resolve();
                        } else {
                            commit(AUTH_FAILED);                            
                            reject(respond);
                        }
                    }).catch(error => {                        
                        reject(error.response.data);
                    });
            });
        },

        logout({ state, commit }) {
            return new Promise((resolve, reject) => {
                auth.logout({ token: state.token.value })
                    .then(respond => {
                        if (respond.status === 200 || respond.status === 201) {
                            commit(AUTH_LOGOUT);
                            resolve("Logged out successfuly");
                        } else {
                            reject('Logout Failed!!!');
                        }
                    }).catch(error => {                        
                        reject(error.response.data);
                    });
            });
        }
    }
}