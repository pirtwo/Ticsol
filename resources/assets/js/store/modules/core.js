import * as MUTATIONS from "../mutation-types";

export const coreModule = {

    namespaced: true,

    state: {
        app: {
            errors: [],
            messages: []
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
        statusbar: {
            show: false,
            message: '',
            icon: ''
        },
        toolbar: {
            show: true,
            height: 0
        },
        header: {
            show: true,
            height: 0
        },
        footer: {
            show: true,
            height: 0
        },
        loading: {
            show: false,
            message: ''
        }

    },

    getters: {

        getAppError: state => id => {
            return state.app.errors[id];
        },

        getAppErrors(state) {
            return state.app.errors;
        },

        getAppMessage: state => id => {
            return state.app.messages[id];
        },

        getAppMessages(state) {
            return state.app.messages;
        },

        getUiFullscreen(state) {
            return state.ui.fullscreen;
        },

        getUiContentWidth(state) {
            return state.ui.contentWidth;
        },

        getUiContentHeight(state) {
            return state.ui.contentHeight;
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

        getStatusbarStatus(state) {
            return state.statusbar.show;
        },

        getStatusbarMessage(state) {
            return state.statusbar.message;
        },

        getStatusbarIcon(state) {
            return state.statusbar.icon;
        },

        getToolbarStatus(state) {
            return state.toolbar.show;
        },

        getToolbarHeight(state) {
            return state.toolbar.height;
        },

        getHeaderStatus(state) {
            return state.header.show;
        },

        getHeaderHeight(state) {
            return state.header.height;
        },

        getFooterStatus(state) {
            return state.footer.show;
        },

        getFooterHeight(state) {
            return state.footer.height;
        },

        getLoadingStatus(state) {
            return state.loading.show;
        },

        getLoadingMessage(state) {
            return state.loading.message;
        }


    },

    mutations: {
        [MUTATIONS.APP_ERRORS_PUSH](state, error) {
            state.app.errors.push(error);
        },

        [MUTATIONS.APP_ERRORS_CLEAR](state) {
            state.app.errors = [];
        },

        [MUTATIONS.APP_MESSAGES_PUSH](state, message) {
            state.app.messages.push(message);
        },

        [MUTATIONS.APP_MESSAGES_CLEAR](state) {
            state.app.messages = [];
        },

        [MUTATIONS.APP_LOADING_STAT](state, payload) {
            state.loading.show = payload.show;
            state.loading.message = payload.message;
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
            state.toolbar.show = payload.show;
            state.toolbar.height = payload.height;
        },

        [MUTATIONS.APP_STATUSBAR](state, payload) {
            state.statusbar.show = payload.show;
            state.statusbar.icon = payload.icon;
            state.statusbar.message = payload.message;
        },

        [MUTATIONS.APP_HEADER](state, payload) {
            state.header.show = payload.show;
            state.header.height = payload.height;
        },

        [MUTATIONS.APP_FOOTER](state, payload) {
            state.footer.show = payload.show;
            state.footer.height = payload.height;
        }

    },

    actions: {
        errorPush({ commit }, { error }) {
            commit(MUTATIONS.APP_ERRORS_PUSH, error);
        },

        errorClear({ commit }) {
            commit(MUTATIONS.APP_ERRORS_CLEAR);
        },

        messagePush({ commit }, { message }) {
            commit(MUTATIONS.APP_MESSAGES_PUSH, message);
        },

        messageClear({ commit }) {
            commit(MUTATIONS.APP_MESSAGES_CLEAR);
        },

        loading({ commit }, { show = true, message = "Loading Please Wait..." }) {
            commit(MUTATIONS.APP_LOADING, { show, message });
        },

        fullscreen({ commit }, { fullscreen }) {
            commit(MUTATIONS.UI_FULLSCREEN, fullscreen);
        },

        documentDimension({ commit }, { width, height }) {
            commit(MUTATIONS.UI_DOCUMENT_DIMENSION, { width, height });
        },

        contentDimension({ commit }, { width, height }) {
            commit(MUTATIONS.UI_CONTENT_DIMENSION, { width, height });
        },

        drawer({ commit }, { show = true, message = "" }) {
            commit(MUTATIONS.APP_DRAWER, { show, message });
        },

        toolbar({ commit }, { show = true, height }) {
            commit(MUTATIONS.APP_TOOLBAR, { show, height });
        },

        statusbar({ commit }, { show, message, icon = "" }) {
            commit(MUTATIONS.APP_STATUSBAR, { show, message, icon });
        },

        header({ commit }, { show, height }) {
            commit(MUTATIONS.APP_HEADER, { show, height });
        },

        footer({ commit }, { show, height }) {
            commit(MUTATIONS.APP_FOOTER, { show, height });
        },

    }

}