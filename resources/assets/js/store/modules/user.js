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
        info: {},
        settings: {}
    },

    getters: {
        getId(state) {
            return state.info.id;
        },

        getClientId(state) {
            return state.info.clientId;
        },

        getInfo(state) {
            return state.info;
        },

        getUsername(state) {
            return state.info.name;
        },

        getFirstname(state) {
            return state.info.firstname;
        },

        getLastname(state) {
            return state.info.lastname;
        },

        getFullname(state) {
            return state.info.fullname;
        },

        getAvatar(state) {
            return state.settings.avatar;
        },

        getScheduleView(state){
            return state.settings.scheduleView;
        },

        getScheduleRange(state){
            return state.settings.scheduleRange;
        },

        getPermissions(state) {
            return state.info.permissions;
        },

        getSettings(state) {
            return state.settings;
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
            state.info.id = payload.id;
            state.info.clientId = payload.client_id;
            state.info.name = payload.name;
            state.info.firstname = payload.firstname;
            state.info.lastname = payload.lastname;
            state.info.fullname = payload.fullname;
            state.info.permissions = payload.permissions;
        },

        [MUTATIONS.USER_SETTINGS](state, payload) {
            state.settings.avatar = payload.avatar;
            state.settings.ical = payload.ical;
            state.settings.theme = payload.theme;
            state.settings.scheduleView = payload.schedule_view;
            state.settings.scheduleRange = payload.schedule_range;
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
                    commit(MUTATIONS.USER_SETTINGS, respond.data.meta);
                    resolve(respond.data);
                }).catch(error => {
                    console.log(error);
                    reject(error);
                });
            })
        },

        updateSettings({ commit }, settings){
            commit(MUTATIONS.USER_SETTINGS, settings);
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