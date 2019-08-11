import Pusher from 'pusher-js';
import * as MUTATIONS from "../mutation-types";
import Notification from '../../utils/notification';

const PUSHER_KEY = '89a2d9bf477c44775c9a';
const PUSHER_CCLUSTER = 'ap1';

export const coreModule = {

    namespaced: true,

    state: {
        notifications: [],

        theme: 'default',

        connection: {
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

        getNotifications(state) {
            return state.notifications;
        },

        getUiFullscreen(state) {
            return state.ui.fullscreen;
        },

        getUiContentWidth(state) {
            return state.ui.contentWidth;
        },

        getUiContentHeight(state) {
            return state.ui.contentHeight - state.toolbar.height + 15;
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

        getTheme(state) {
            return state.theme;
        }
    },

    mutations: {
        [MUTATIONS.APP_NOTIF_PUSH](state, notification) {
            state.notifications.push(notification);
        },

        [MUTATIONS.APP_NOTIF_SEEN](state, id) {
            state.notifications.find(item => item.id == id).seen = true;
        },

        [MUTATIONS.APP_NOTIF_HIDE](state, id) {
            state.notifications.find(item => item.id == id).hide = true;
        },

        [MUTATIONS.APP_NOTIF_CLEAR](state) {
            state.notifications = [];
        },

        [MUTATIONS.APP_CONNECTION](state, payload) {
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
        notify({ commit }, payload) {
            commit(MUTATIONS.APP_NOTIF_PUSH, payload);
        },

        seenNotification({ commit }, id) {
            commit(MUTATIONS.APP_NOTIF_SEEN, id);
        },

        hideNotification({ commit }, id) {
            commit(MUTATIONS.APP_NOTIF_HIDE, id);
        },

        clearNotificationLog({ commit }) {
            commit(MUTATIONS.APP_NOTIF_CLEAR);
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

        theme({ commit }, payload) {
            commit(MUTATIONS.APP_THEME, payload.toLowerCase());
        },

        connectToChannels({ state, dispatch, commit, rootState }) {
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

            let userChannel = pusher.subscribe(`private-App.Users.${user.info.id}`);
            userChannel.bind("User.Update", (data) => {
                //console.log(data);
                dispatch('resource/onServerUpdate', data.resName, { root: true });
            });

            let clientChannel = pusher.subscribe(`private-App.Clients.${user.info.clientId}`);
            clientChannel.bind("Client.Update", (data) => {
                //console.log(data);
                dispatch('resource/onServerUpdate', data.resName, { root: true });
            });

            pusher.connection.bind('state_change', (state) => {
                commit(MUTATIONS.APP_CONNECTION, state);

                // Notify user about connection state
                // by sending the notifications.
                let msg = '';
                let type = 'info';
                let title = 'Connection';
                let autohide = true;

                if (state.current == 'connecting') {
                    title = 'Connecting';
                    msg = 'Connecting to the server, please wait...';
                } else if (state.current == 'connected') {
                    title = 'Connected';
                    type = 'connected';
                    msg = 'Connected to the server successfuly.';
                } else if (state.current == 'unavailable') {
                    autohide = false;
                    title = 'Disconnected';
                    type = 'disconnected';
                    msg = 'The connection is unavailable, please check your internet connection.';
                } else if (state.current == 'failed') {
                    autohide = false;
                    title = 'Disconnected';
                    type = 'disconnected';
                    msg = 'Connection failed, Channels is not supported by the browser.';
                }

                if (msg) dispatch('notify', new Notification({
                    type: type,
                    title: title,
                    message: msg,
                    autoHide: autohide
                }));
            });

            userChannel.bind('pusher:subscription_succeeded', () => {
                console.log('subscribed to user channel successfuly.');
            });
            userChannel.bind('pusher:subscription_error', () => {
                console.log('subscribe to user channel failed.');
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