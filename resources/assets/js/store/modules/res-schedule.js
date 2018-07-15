
import { api } from '../../api/http';
import * as URL from '../../api/resources';
import {
    SCHEDULE_EVENTS,
    SCHEDULE_EVENTS_ADD,
    SCHEDULE_EVENTS_UPDATE,
    SCHEDULE_EVENTS_DELETE,
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
            if (state.events.length == 0 || state.resourceType === '') return state.events;
            switch (state.resourceType) {
                case 'job':
                    return state.events.map(obj => {
                        return {
                            id: obj.id,
                            resource: obj.job_id,
                            start: obj.start,
                            end: obj.end,
                            text: obj.user.name,
                            complete: 30
                        };
                    });
                case 'user':
                    return state.events.map(obj => {
                        return {
                            id: obj.id,
                            resource: obj.user_id,
                            start: obj.start,
                            end: obj.end,
                            text: obj.job.title,
                            complete: 30
                        };
                    });
                default:
                    throw new Error('Invalid resource type.');
            }
        },

        getResources: (state) => {
            if (state.resources.length == 0) return state.resources;
            switch (state.resourceType) {
                case 'job':
                    return state.resources.data.map(obj => {
                        return { id: obj.id, name: obj.title };
                    });
                case 'user':
                    return state.resources.data.map(obj => {
                        let meta = JSON.parse(obj.meta);
                        return { id: obj.id, name: obj.name, avatar: meta.avatar };
                    });
                default:
                    throw new Error('Invalid resource type.');
            }
        }
    },

    mutations: {

        [SCHEDULE_EVENTS](state, payload) {
            state.events = payload.data;
        },

        [SCHEDULE_EVENTS_ADD](state, payload) {            
            state.events.push(payload[0]);
        },

        [SCHEDULE_EVENTS_UPDATE](state, { id, payload }) {            
            let index = state.events.findIndex((el) => {
                return el.id === id;
            });

            state.events[index] = payload[0];            
        },

        [SCHEDULE_EVENTS_DELETE](state, { id, payload }) {

        },

        [SCHEDULE_RESOURCE](state, payload) {
            state.resources = payload;
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
                        if (respond.status === 200) {
                            commit(SCHEDULE_EVENTS, { data: respond.data });
                            resolve(respond.data);
                        } else
                            reject(respond);
                    }).catch(error => {
                        console.log(error);
                    });
            });
        },

        create({ state, commit }, { data }) {
            return new Promise((resolve, reject) => {
                api.create(URL.SCHEDULE_CREATE, {
                    user_id: data.userId,
                    job_id: data.resourceId,
                    start: data.start,
                    end: data.end,
                    offsite: data.offsite,
                    break_length: 0
                }).then(respond => {
                    if (respond.status === 200) {
                        commit(SCHEDULE_EVENTS_ADD, respond.data);
                        resolve(true);
                    }
                    else reject(respond);
                });
            }).catch(error => {
                console.log(error);
            });
        },

        update({ commit }, { id, data }) {
            return new Promise((resolve, reject) => {
                api.update(`${URL.SCHEDULE_UPDATE}/${id}`, data)
                    .then(respond => {
                        if (respond.status === 200) {
                            commit(SCHEDULE_EVENTS_UPDATE, { id: id, payload: respond.data });
                            resolve(true);
                        }
                        else reject(respond);
                    }).catch(error => {
                        console.log(error);
                    });
            });
        },

        delete({ commit }, { id }) {
            return new Promise((resolve, reject) => {
                api.update(`${URL.SCHEDULE_DELETE}/${id}`)
                    .then(respond => {
                        if (respond.status === 200) {
                            commit(SCHEDULE_EVENTS_UPDATE, { id: id });
                            resolve(true);
                        }
                        else reject(respond);
                    }).catch(error => {
                        console.log(error);
                    });
            });
        }
    }
} 