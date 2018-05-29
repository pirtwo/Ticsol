import {
    LOADING_START,
    LOADING_STOP,
    LOADING_MESSAGE,
} from '../mutation-types';

export const loadingModule = {
    namespaced: true,
    state: {
        isLoading: false,
        message: '',
    },
    getters: {
        isLoading: (state) => {
            return state.isLoading;
        },
        getMessage: (state) => {
            return state.message;
        }
    },
    mutations: {
        [LOADING_START](state) {
            state.isLoading = true;
        },
        [LOADING_STOP](state) {
            state.isLoading = false;
        },
        [LOADING_MESSAGE](state, { message }) {
            state.message = message;
        }
    },
    actions: {
        state({ state, commit }, { isLoading, message }) {
            if (isLoading)
                commit(LOADING_START);
            else
                commit(LOADING_STOP);
            commit(LOADING_MESSAGE, { message });
        },

        start({ state, commit }, { message }) {
            commit(LOADING_START);
            commit(LOADING_MESSAGE, { message });
        },

        stop({ state, commit }, { message }) {
            commit(LOADING_STOP);
            commit(LOADING_MESSAGE, { message });
        },

        message({ state, commit }, { message }) {
            commit(LOADING_MESSAGE, { message });
        }
    },
}