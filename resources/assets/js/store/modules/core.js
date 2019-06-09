import Pusher from 'pusher-js';
import * as MUTATIONS from "../mutation-types";

const PUSHER_KEY = '89a2d9bf477c44775c9a';
const PUSHER_CCLUSTER = 'ap1';

export const coreModule = {

    namespaced: true,

    state: {
        logs: [],

        theme: 'default',

        connection:{
            previous: '',
            current: ''
        },

        ui: {
            fullscreen: false,
            contentWidth: 0,
            contentHeight: 0,
            documentWidth: 0,
            documentHeight: 0
        },

        drawer: {
            show: true,
            message: ''
        },

        snackbar: {
            show: false,
            message: '',
            theme: '',
            fixed: false,
            timeout: 300
        },

        toolbar: {
            show: true,
            height: 0
        },
    },

    getters: {

        getAppLogs(state) {
            return state.logs;
        },

        getUiFullscreen(state) {
            return state.ui.fullscreen;
        },

        getUiContentWidth(state) {
            return state.ui.contentWidth;
        },

        getUiContentHeight(state) {
            return state.ui.contentHeight - state.toolbar.height;
        },

        getUiDocWidth(state) {
            return state.ui.documentWidth;
        },

        getUiDocHeight(state) {
            return state.ui.documentHeight;
        },

        getDrawerStatus(state) {
            return state.drawer.show;
        },

        getDrawerMessage(state) {
            return state.drawer.message;
        },

        getSnackbar(state) {
            return state.snackbar;
        },

        getToolbarStatus(state) {
            return state.toolbar.show;
        },

        getToolbarHeight(state) {
            return state.toolbar.height;
        },

        getTheme(state){
            return state.theme;
        } 
    },

    mutations: {
        [MUTATIONS.APP_LOG_PUSH](state, log) {
            state.logs.push(log);
        },

        [MUTATIONS.APP_LOG_CLEAR](state) {
            state.logs = [];
        },

        [MUTATIONS.APP_CONNECTION](state, payload){
            state.connection.previous = payload.previous;
            state.connection.current = payload.current;
        },

        [MUTATIONS.APP_THEME](state, payload) {
            state.theme = payload;
        },

        [MUTATIONS.APP_FULLSCREEN](state, isFullScreen) {
            state.ui.fullscreen = isFullScreen;
        },

        [MUTATIONS.APP_DOCUMENT_DIMENSION](state, dimension) {
            state.ui.documentWidth = dimension.width;
            state.ui.documentHeight = dimension.height;
        },

        [MUTATIONS.APP_CONTENT_DIMENSION](state, dimension) {
            state.ui.contentWidth = dimension.width;
            state.ui.contentHeight = dimension.height;
        },

        [MUTATIONS.APP_DRAWER](state, payload) {
            state.drawer.show = payload.show;
            state.drawer.message = payload.message;
        },

        [MUTATIONS.APP_TOOLBAR](state, payload) {
            state.toolbar.show = payload.show | state.toolbar.show;
            state.toolbar.height = payload.height;
        },

        [MUTATIONS.APP_SNACKBAR](state, payload) {
            state.snackbar.show = payload.show;
            state.snackbar.message = payload.message;
            state.snackbar.theme = payload.theme;
            state.snackbar.fixed = payload.fixed;
            state.snackbar.timeout = payload.timeout;
        }
    },

    actions: {
        pushLog({ commit }, payload) {
            commit(MUTATIONS.APP_LOG_PUSH, payload);
        },

        clearLog({ commit }) {
            commit(MUTATIONS.APP_LOG_CLEAR);
        },
       
        fullscreen({ commit }, { enable }) {
            commit(MUTATIONS.APP_FULLSCREEN, enable);
        },

        documentDimension({ commit }, { width, height }) {
            commit(MUTATIONS.APP_DOCUMENT_DIMENSION, { width, height });
        },

        contentDimension({ commit }, { width, height }) {
            commit(MUTATIONS.APP_CONTENT_DIMENSION, { width, height });
        },

        drawer({ commit }, { show = true, message = "" }) {
            commit(MUTATIONS.APP_DRAWER, { show, message });
        },

        toolbar({ commit }, { height, show }) {
            commit(MUTATIONS.APP_TOOLBAR, { show, height });
        },
       
        snackbar({ commit }, { show, message, theme = '', fixed = false, timeout = 300 }) {
            commit(MUTATIONS.APP_SNACKBAR, { show: false, message: '', theme: theme });
            commit(MUTATIONS.APP_SNACKBAR, { show, message, theme, fixed, timeout });
            if (!fixed) {
                setTimeout(() => {
                    commit(MUTATIONS.APP_SNACKBAR, { show: false, message: '', theme: theme });
                }, timeout);
            }
        }, 
        
        theme({ commit }, payload){
            commit(MUTATIONS.APP_THEME, payload.toLowerCase());
        },
        
        connectToChannels({state, dispatch, commit, rootState}){
            if (!rootState.user.isAuth) return

            let user = rootState.user;            

            let pusher = new Pusher(PUSHER_KEY, {
                cluster: PUSHER_CCLUSTER,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + user.token.value
                    }
                }
            });

            //Pusher.logToConsole = true;
            
            let notifChannel = pusher.subscribe(`private-App.Users.${user.info.id}`);
            notifChannel.bind("User.Update", (data) => {
                dispatch('resource/onClientUpdate', data.resName, { root: true });
            });

            let clientChannel = pusher.subscribe(`private-App.Clients.${user.info.client_id}`);
            clientChannel.bind("Client.Update", (data) => {
                dispatch('resource/onClientUpdate', data.resName, { root: true });
            });

            pusher.connection.bind('state_change', (state) => {
                commit(MUTATIONS.APP_CONNECTION, state);
            });

            notifChannel.bind('pusher:subscription_succeeded', () => {
                console.log('subscribed to notification channel successfuly.');
            });
            notifChannel.bind('pusher:subscription_error', () => {
                console.log('subscribe to notification channel failed.');
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