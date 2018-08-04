import { api } from "../../api/http";
import * as URLs from "../../api/resources";
import * as MUTATIONS from "../mutation-types";

export const userModule = {
    namespaced: true,

    state: {
        user: {
            name: "",
            roles: []
        },
        auth: {
            isAuth: false,
            token: {
                value: "",
                expire: ""
            }
        },
        settings: {
            avatar: "",
            firstName: "",
            lastName: ""
        }
    },

    getters: {
        getUser(state) {
            return state.user;
        },

        getIsAuth(state) {
            return state.auth.isAuth;
        },

        getToken(state) {
            return state.auth.token;
        },

        getSettings(state) {
            return state.settings;
        }
    },

    mutations: {
        [MUTATIONS.USER_INFO](state, payload) {
            state.user = payload;
        },

        [MUTATIONS.USER_AUTH_SUCCESS](state) {
            state.auth.isAuth = true;
        },

        [MUTATIONS.USER_AUTH_FAILE](state) {
            state.auth.isAuth = false;
        },

        [MUTATIONS.USER_AUTH_TOKEN](state, payload) {
            state.auth.token.value = payload.value;
            state.auth.token.expire = payload.expire;
        },

        [MUTATIONS.USER_AUTH_LOGOUT](state) {
            state.auth.isAuth = false;
            state.auth.token.value = "";
            state.auth.token.expire = "";
            state.user = {};
            state.settings = {};
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

        settings() {

        }
    }
}