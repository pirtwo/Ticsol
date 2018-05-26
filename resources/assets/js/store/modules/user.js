
import { auth } from '../../api/http';
import {
    USER_OBJECT,
    USRE_TOKKEN,
    USER_AUTHENTICATED,
    USER_NOTAUTHENTICATED
} from '../mutation-types';

export const userModule = {
    namespaced: true,
    state: {
        counter: 0,
        isAuth: false,
        user: {
            id: -1,
            name: 'Guest',
            avatar: '',
        },
        token: {
            value: '',
            scope: '',
            expire: '',
            issued: '',
        }
    },
    getters: {
        userIsAuth: (state) => {
            return state.isAuth;
        },
        userInfo: (state) => {
            return state.user;
        },
        userToken: (state) => {
            return state.token;
        },
        getTick: (state) => {
            return state.counter;
        },
        getTick: (state) => {
            return state.counter;
        },
        routePath: (state, getters, rootState) => {
            return rootState.route.path;
        }
    },
    mutations: {

        [USER_OBJECT](state, payload) {
            state.user.id = payload.userId;
            state.user.name = payload.username;
            state.user.avatar = payload.userAvatar;
        },

        [USRE_TOKKEN](state, payload) {
            state.token.value = payload.access_token;
            state.token.scope = payload.scope;
            state.token.expire = payload.expires_in;
            state.token.issued = payload.issued;
        },

        [USER_AUTHENTICATED](state) {
            state.isAuth = true;
        },

        [USER_NOTAUTHENTICATED](state) {
            state.isAuth = false;
        },

        tickCounter(state) {
            state.counter++;
        }

    },
    actions: {

        tick({ state, commit }) {
            commit('tickCounter');
        },

        login({ state, commit, getters }, payload) {
            return new Promise((resolve, reject) => {
                auth.login(payload).then(respond => {
                    if (respond.status === 200) {
                        commit(USRE_TOKKEN, respond.data);
                        commit(USER_AUTHENTICATED);
                        console.log(getters.routePath);
                        resolve(respond);
                    } else {
                        commit(USER_NOTAUTHENTICATED);
                        console.log('reject');
                        reject(respond);
                    }
                }).catch(error => {
                    console.log('error');
                    reject(error.response.data);
                });
            });
        },

        logout({ state, commit }, payload) {
            auth.logout(payload).then(respond => {
                if (respond.status === 201) {
                    commit(USER_NOTAUTHENTICATED);
                }
            });
        },
    }
} 