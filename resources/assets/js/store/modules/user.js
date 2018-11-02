import { api } from "../../api/http";
import * as URLs from "../../api/resources";
import * as MUTATIONS from "../mutation-types";
import { Date } from "core-js";

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
        getInfo(state) {
            return state.info;
        },

        getUsername(state) {
            if (state.info === null) return '';
            return state.info.name;
        },

        getAvatar(state) {
            if (state.info === null) return '';
            return state.info.meta.avatar;
        },

        getSettings(state) {
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
            console.log(payload);
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
        }

    },

    actions: {
        login({ commit }, payload) {
            return new Promise((resolve, reject) => {
                api.post(URLs.AUTH_LOGIN, payload, null, true, false)
                    .then(respond => {
                        commit(MUTATIONS.USER_AUTH_TOKEN, respond.data);
                        commit(MUTATIONS.USER_AUTH_SUCCESS);
                        resolve("Success");
                    }).catch(error => {
                        console.log(error);
                        commit(MUTATIONS.USER_AUTH_FAILE);
                        reject(error);
                    });
            });
        },

        logout({ commit }) {
            return new Promise((resolve, reject) => {
                api.post(URLs.AUTH_LOGOUT, null)
                    .then(() => {
                        commit(MUTATIONS.USER_AUTH_LOGOUT);
                        resolve("Success");
                    }).catch(error => {
                        reject(error);
                    });
            });
        },

        refresh() {

        },

        info({ commit, dispatch }) {
            return new Promise((resolve, reject) => {
                api.get(URLs.USER_INFO, null).then(respond => {
                    commit(MUTATIONS.USER_INFO, respond.data);
                    resolve(respond);
                }).catch(error => {                    
                    console.log(error);
                    reject(error);
                });
            })
        }
    }
}