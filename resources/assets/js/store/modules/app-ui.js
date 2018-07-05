
import {
    UI_MAIN_HEIGHT,
    UI_WINDOW_HEIGHT,
    UI_TOOLBAR_HEIGHT,
    UI_CONTENT_HEIGHT
} from '../mutation-types';

export const UIModule = {
    namespaced: true,
    state: {
        mainHeight: 0,
        windowHeight: 0,
        toolbarHeight: 70,
        contentHeight: 0
    },
    getters: {
        getWindowHeight: (state) => {
            return state.windowHeight;
        },
        getMainHeight: (state) => {
            return state.mainHeight;
        },
        getToolbarHeight: (state) => {
            return state.toolbarHeight;
        },
        getContentHeight:(state)=>{
            return state.mainHeight - state.toolbarHeight;
        }
    },
    mutations: {
        [UI_MAIN_HEIGHT](state, payload) {
            state.mainHeight = payload;
        },

        [UI_WINDOW_HEIGHT](state, payload) {
            state.windowHeight = payload;
        },

        [UI_TOOLBAR_HEIGHT](state, payload) {
            state.toolbarHeight = payload;
        },

        [UI_CONTENT_HEIGHT](state, payload) {
            state.contentHeight = payload;
        }
    },
    actions: {
        setWindowHeight({ commit }, { height }) {
            commit(UI_WINDOW_HEIGHT, height);
        },

        setMainHeight({ commit }, { height }) {            
            commit(UI_MAIN_HEIGHT, height);
        },

        setToolbarHeight({ commit }, { height }) {            
            commit(UI_TOOLBAR_HEIGHT, height);
        },

        setContentHight({ commit }, { height }) {            
            commit(UI_CONTENT_HEIGHT, height);
        }
    }
} 