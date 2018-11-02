import * as MUTATIONS from "../mutation-types";

export const coreModule = {

    namespaced: true,

    state: {
        /**
         * @param {string}  type     primary, secondary, success, danger, warning, info
         * @param {object}  date     date and time of log.
         * @param {boolean} seen     is seen by user.
         * @param {string}  title    The title of log.
         * @param {string}  content  the log content.
         * @param {string}  footer   the content of footer
         */
        logs:[],

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
        [MUTATIONS.APP_LOG_PUSH](state, log) {
            state.logs.push(log);
        },

        [MUTATIONS.APP_LOG_CLEAR](state) {
            state.logs = [];
        },

        [MUTATIONS.APP_LOADING](state, payload) {
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
            state.toolbar.show = payload.show | state.toolbar.show;
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
        pushLog({ commit }, payload) {
            commit(MUTATIONS.APP_LOG_PUSH, payload);
        },

        clearLog({ commit }) {
            commit(MUTATIONS.APP_LOG_CLEAR);
        },
        
        loading({ commit }, { show = true, message = "Loading Please Wait..." }) {
            commit(MUTATIONS.APP_LOADING, { show, message });
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