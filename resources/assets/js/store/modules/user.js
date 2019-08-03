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
            type: "",
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

        getFirstname(state) {
            if (!state.info) return "";
            return state.info.firstname;
        },

        getLastname(state) {
            if (!state.info) return "";
            return state.info.lastname;
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

        [MUTATIONS.USER_AUTH_TOKEN](state, token) {
            console.log(token.value);
            state.token = token;
        },

        [MUTATIONS.USER_AUTH_LOGOUT](state) {
            state.isAuth = false;
            state.token.value = "";
            state.token.expire = "";
            state.info = null;
        }

    },

    actions: { 
        
        successfulAuth({ commit }) {
            commit(MUTATIONS.USER_AUTH_SUCCESS);
        },

        extractToken({ commit }, hash) {
            return new Promise((resolve, reject) => {
                let regx = /^#access_token=([\w.-]+)&token_type=(\w+)&expires_in=(\d+)$/m;
                if (!regx.test(hash))
                    reject(false);
                let token = regx.exec(hash);
                let payload = { value: token[1], type: token[2], expire: token[3] };
                commit(MUTATIONS.USER_AUTH_TOKEN, payload);
                resolve(true);
            });
        },

        me({ commit }) {
            return new Promise((resolve, reject) => {
                api.get({ url: URLs.USER_INFO }).then(respond => {
                    commit(MUTATIONS.USER_INFO, respond.data);
                    resolve(respond.data);
                }).catch(error => {
                    console.log(error);
                    reject(error);
                });
            })
        },

        updateInfo({ commit }, info){
            commit(MUTATIONS.USER_INFO, info);
        },

        logout({ commit, dispatch }) {
            api.post({ url: URLs.AUTH_LOGOUT }).then(() => {
                commit(MUTATIONS.USER_AUTH_LOGOUT);
                dispatch('core/clearNotificationLog', null, { root: true });
            }).catch(error => {
                console.log(error);
            });
        },

        checkPermission({ state }, permission) {
            return state.info.permission.indexOf(permission) > -1;
        }
    }
}