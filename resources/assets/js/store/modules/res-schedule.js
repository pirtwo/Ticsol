
import { api } from '../../api/http';
import * as URL from '../../api/resources';
import {
    SCHEDULE_EVENTS,
    SCHEDULE_EVENTS_ADD,
    SCHEDULE_RESOURCE,
    SCHEDULE_RESOURCE_URL,
    SCHEDULE_RESOURCE_TYPE
} from '../mutation-types';

export const scheduleModule = {
    namespaced: true,
    state: {
        events: [],
        resources: [],
        resourceType: '',
        resourceUrl: '',
    },
    getters: {
        getEvents: (state) => {
            return state.events;
        },

        getResources: (state) => {
            return state.resources;
        }
    },
    mutations: {
        [SCHEDULE_EVENTS](state, payload) {
            switch (state.resourceType) {
                case 'job':
                    state.events = payload.data.map(obj => {
                        return {
                            id: obj.id,
                            resource: obj.job_id,
                            start: obj.start,
                            end: obj.end,
                            text: obj.user.name
                        };
                    });
                    break;
                case 'user':
                    state.events = payload.data.map(obj => {
                        return {
                            id: obj.id,
                            resource: obj.user_id,
                            start: obj.start,
                            end: obj.end,
                            text: obj.job.title
                        };
                    });
                    break;
                default:
                    throw new Error('Invalid resource type.');                    
            }
        },

        [SCHEDULE_EVENTS_ADD](state, payload) {
            console.log(payload);
            var obj = payload[0];
            switch (state.resourceType) {
                case 'job':
                    state.events.push({
                        id: obj.id,
                        resource: obj.job_id,
                        start: obj.start,
                        end: obj.end,
                        text: obj.user.name
                    });
                    break;
                case 'user':
                    state.events.push({
                        id: obj.id,
                        resource: obj.user_id,
                        start: obj.start,
                        end: obj.end,
                        text: obj.job.name
                    });
                    break;
                default:
                    throw new Error('Invalid resource type.');
            }
        },

        [SCHEDULE_RESOURCE](state, payload) {
            console.log(payload);
            switch (state.resourceType) {
                case 'job':
                    state.resources = payload.data.map(obj => {
                        return { id: obj.id, name: obj.title };
                    });
                    break;
                case 'user':
                    state.resources = payload.data.map(obj => {
                        return { id: obj.id, name: obj.name };
                    });
                    break;
                default:
                    throw new Error('Invalid resource type.');
            }
        },

        [SCHEDULE_RESOURCE_TYPE](state, payload) {
            if (['job', 'user'].indexOf(payload) == -1)
                throw new Error('Invalid resource type.');
            state.resourceType = payload;
        },

        [SCHEDULE_RESOURCE_URL](state, payload) {
            switch (payload) {
                case 'job':
                    state.resourceUrl = URL.JOB_LIST;
                    break;
                case 'user':
                    state.resourceUrl = URL.USER_LIST;
                    break;
                default:
                    throw new Error('Invalid resource type.');
            }
        }

    },
    actions: {
        initi({ commit, dispatch }, { resource }) {
            return new Promise((resolve) => {
                commit(SCHEDULE_RESOURCE_TYPE, resource);
                commit(SCHEDULE_RESOURCE_URL, resource);               
                dispatch('fetchResource').then(() => {
                    dispatch('fetchEvents').then(() => {
                        resolve();
                    });
                });
            }).catch(error => {
                console.log(error);
            });
        },

        fetchResource({ state, commit }) {
            return new Promise((resolve, reject) => {                
                api.list(state.resourceUrl)
                    .then(respond => {
                        console.log(respond.data);
                        if (respond.status === 200) {                            
                            commit(SCHEDULE_RESOURCE, { data: respond.data });                            
                            resolve();
                        } else
                            reject(respond);
                    });
            });

        },

        fetchEvents({ commit }) {
            return new Promise((resolve, reject) => {
                api.list(URL.SCHEDULE_LIST)
                    .then(respond => {
                        console.log(respond.data);
                        if (respond.status === 200) {
                            commit(SCHEDULE_EVENTS, { data: respond.data });
                            resolve();
                        } else
                            reject(respond);
                    });
            });
        },

        create({ state, commit }, { data }) {
            return new Promise((resolve, reject) => {
                let userId = state.resourceType == 'user' ? data.resourceId : data.userId;
                let jobId = state.resourceType == 'job' ? data.resourceId : data.userId;
                api.create(URL.SCHEDULE_CREATE, {
                    user_id: userId,
                    job_id: jobId,
                    start: data.start,
                    end: data.end,
                    offsite: false,
                    break_length: 0
                }).then(respond => {
                    if (respond.status === 200) {
                        commit(SCHEDULE_EVENTS_ADD, respond.data);
                        resolve();
                    }
                    else reject(respond);
                });
            }).catch(error => {
                console.log(error);
            });
        },

        update() {

        },

        delete() {

        }
    }
} 