import {
    user,
    job,
} from '../../api/http';
import {
    SIDEBAR_RESOURCE,
    SIDEBAR_RESOURCE_TYPE
} from '../mutation-types';

export const sidebarModule = {
    namespaced: true,
    state: {
        resource: {},
        resourceType: '',
    },
    getters: {
        getResource: (state) => {
            return state.resource;
        },
        getResourceType: (state) => {
            return state.resourceType;
        }
    },
    mutations: {
        [SIDEBAR_RESOURCE](state, payload) {
            state.resource = payload;
        },
        [SIDEBAR_RESOURCE_TYPE](state, payload) {
            state.resourceType = payload;
        }
    },
    actions: {
        listUsers({ state, commit }) {
            return new Promise((resolve, reject) => {
                user.list()
                    .then((respond) => {
                        if (respond.status === 200 || respond.status === 201) {
                            commit(SIDEBAR_RESOURCE_TYPE, 'employee');
                            commit(SIDEBAR_RESOURCE, respond.data);                            
                            resolve();
                        } else {
                            reject(respond);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        reject(error);
                    });
            });
        },
        listJobs({ state, commit }) {
            return new Promise((resolve, reject) => {
                job.list()
                    .then((respond) => {
                        if (respond.status === 200 || respond.status === 201) {
                            commit(SIDEBAR_RESOURCE_TYPE, 'job');
                            commit(SIDEBAR_RESOURCE, respond.data);                            
                            resolve();
                        } else {
                            reject(respond);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        reject(error);
                    });
            });
        }
    }
}