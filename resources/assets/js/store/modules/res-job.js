
import { api } from '../../api/http';
import * as URL from '../../api/resources';
import {
    JOB_LIST,
    JOB_ERRORS
} from '../mutation-types';

export const jobModule = {
    namespaced: true,

    state: {
        list: [],
        selected: [],
        error: {
            message: '',
            errors: []
        }
    },

    getters: {
        getJobList: (state) => {
            return state.list;
        },

        getErrors: (state) => {
            return state.error.errors;
        },

        getErrorMessage: (state) => {
            return state.error.message;
        }
    },

    mutations: {
        [JOB_LIST](state, payload) {
            state.list = payload;
        },

        [JOB_ERRORS](state, payload) {
            state.error.message = payload.response.data.message;
            state.error.errors = payload.response.data.errors;
        }
    },

    actions: {
        list({ state, commit }, { query }) {
            return new Promise((resolve, reject) => {
                api.list(URL.JOB_LIST, query)
                    .then(respond => {
                        if (respond.status == 200) {
                            commit(JOB_LIST, respond.data);
                            resolve(respond.data);
                        } else {
                            reject(respond.data);
                        }
                    }).catch(error => {
                        commit(JOB_ERRORS, error);
                        console.log(error.response);
                    });
            });
        },

        create({ state, commit }, { payload }) {
            return new Promise((resolve, reject) => {
                api.create(URL.JOB_CREATE, payload)
                    .then(respond => {
                        if (respond.status === 200 || respond.status === 201) {
                            resolve(respond.data);
                        } else {
                            reject(respond.data);
                        }
                    }).catch(error => {
                        commit(JOB_ERRORS, error);
                        console.log(error.response);
                        //console.log(error.request);
                        //console.log(error.message);
                        //console.log(error.config);
                    });
            });
        },

        update({ state, commit }, { payload }) {
            return new Promise((resolve, reject) => {
                api.update(URL.JOB_UPDATE, payload)
                    .then(respond => {
                        if (respond.status == 200) {
                            resolve(respond.data);
                        } else {
                            reject(respond.data);
                        }
                    }).catch(error => {
                        commit(JOB_ERRORS, error);
                        console.log(error.response);
                    });
            });
        }
    }
} 