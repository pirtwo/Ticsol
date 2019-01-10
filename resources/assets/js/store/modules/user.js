import Echo from "laravel-echo";
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
            return state.info.id;
        },

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
        login({ commit, dispatch }, payload) {
            return new Promise((resolve, reject) => {
                api.post(URLs.AUTH_LOGIN, payload, null, true, false)
                    .then(respond => {
                        commit(MUTATIONS.USER_AUTH_TOKEN, respond.data);
                        commit(MUTATIONS.USER_AUTH_SUCCESS);
                        //dispatch('subscribeForNotifications');
                        resolve("success");
                    }).catch(error => {
                        console.log(error);
                        commit(MUTATIONS.USER_AUTH_FAILE);
                        reject(error);
                    });
            });
        },

        logout({ commit }) {
            api.post(URLs.AUTH_LOGOUT, null);
            commit(MUTATIONS.USER_AUTH_LOGOUT);
        },

        refresh() {

        },

        info({ commit, dispatch }) {
            return new Promise((resolve, reject) => {
                api.get(URLs.USER_INFO, null).then(respond => {
                    commit(MUTATIONS.USER_INFO, respond.data);
                    resolve(respond.data);
                }).catch(error => {
                    console.log(error);
                    reject(error);
                });
            })
        },

        subscribeForNotifications({ state, dispatch }) {
            if (!state.isAuth) return

            let pusher = new Pusher("8cf467561b944c1668e0", {
                cluster: "ap2",
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + state.token.value
                    }
                }
            });

            pusher.logToConsole = true;

            let notifChannel = pusher.subscribe(`private-App.Users.${state.info.id}`);
            notifChannel.bind("Illuminate\\Notifications\\Events\\BroadcastNotificationCreated", (data) => {
                console.log(data);
            });

            let clientChannel = pusher.subscribe(`private-App.Clients.${state.info.client_id}`);
            clientChannel.bind("Client.Update", (data) => {                
                dispatch('resource/onClientUpdate', data.resName, { root: true });
            });

            notifChannel.bind('pusher:subscription_succeeded', () => {
                console.log('subscribed to notif channel successfuly.');
            });
            notifChannel.bind('pusher:subscription_error', () => {
                console.log('subscribe to notif channel failed.');
            });

            clientChannel.bind('pusher:subscription_succeeded', () => {
                console.log('subscribed to client channel successfuly.');
            });

            clientChannel.bind('pusher:subscription_error', () => {
                console.log('subscribe to client channel failed.');
            });

        }
    }
}