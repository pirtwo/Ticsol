import Pusher from 'pusher-js';
import { api } from "../../api/http";
import * as URLs from "../../api/resources";
import * as MUTATIONS from "../mutation-types";

export const userModule = {
    namespaced: true,

    state: {
        roles: [],
        isAuth: false,
        token: {
            value: "",
            expire: ""
        },
        info: null
    },

    getters: {
        getId(state) {
            if (!state.info) return "";
            return state.info.id;
        },

        getInfo(state) {
            if (!state.info) return "";
            return state.info;
        },

        getUsername(state) {
            if (!state.info) return "";
            return state.info.name;
        },

        getFullname(state) {
            if (!state.info) return "";
            return state.info.fullname;
        },

        getAvatar(state) {
            if (!state.info) return "";
            if (state.info.meta.avatar) {
                return state.info.meta.avatar;
            } else {
                return '/img/avatar/default.png';
            }
        },

        getPermissions(state) {
            if (!state.info) return [];
            return state.info.permissions;
        },

        getSettings(state) {
            if (!state.info) return [];
            return state.info.meta;
        },

        getIsAuth(state) {
            return state.isAuth;
        },

        getToken(state) {
            return state.token;
        }
    },

    mutations: {
        [MUTATIONS.USER_INFO](state, payload) {
            state.info = payload;
        },

        [MUTATIONS.USER_AUTH_SUCCESS](state) {
            state.isAuth = true;
        },

        [MUTATIONS.USER_AUTH_FAILE](state) {
            state.isAuth = false;
        },

        [MUTATIONS.USER_AUTH_TOKEN](state, payload) {
            console.log(payload.access_token);
            state.token.value = payload.access_token;
            state.token.expire = payload.expires_in;
        },

        [MUTATIONS.USER_AUTH_LOGOUT](state) {
            state.isAuth = false;
            state.token.value = "";
            state.token.expire = "";
            state.info = null;
        }

    },

    actions: {
        login({ commit, dispatch }, payload) {
            return new Promise((resolve, reject) => {
                api.post({ url: URLs.AUTH_LOGIN, data: payload, query: null, isJson: true, isAuth: false })
                    .then(respond => {
                        commit(MUTATIONS.USER_AUTH_TOKEN, respond.data);
                        commit(MUTATIONS.USER_AUTH_SUCCESS);
                        resolve("success");
                    }).catch(error => {
                        console.log(error);
                        commit(MUTATIONS.USER_AUTH_FAILE);
                        reject(error);
                    });
            });
        },

        logout({ commit, dispatch }) {
            api.post({ url: URLs.AUTH_LOGOUT, data: null });
            commit(MUTATIONS.USER_AUTH_LOGOUT);
            dispatch('core/clearNotificationLog', null, { root: true });
        },

        refresh() {
            //
        },

        info({ commit, dispatch }) {
            return new Promise((resolve, reject) => {
                api.get({ url: URLs.USER_INFO, data: null }).then(respond => {
                    commit(MUTATIONS.USER_INFO, respond.data);
                    resolve(respond.data);
                }).catch(error => {
                    console.log(error);
                    reject(error);
                });
            })
        },

        checkPermission({ state }, permission) {
            return state.info.permission.indexOf(permission) > -1;
        }
    }
}