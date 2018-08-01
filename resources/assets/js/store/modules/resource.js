import { api } from "../../api/http";
import * as URLs from "../../api/resources";
import * as MUTATIONS from "../mutation-types";

export const resourceModule = {
    namespaced: true,

    state: {
        user: {
            list: [],
            listURL: URLs.USER_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        role: {
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        acl: {
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        request: {
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        job: {
            list: [],
            listURL: URLs.JOB_LIST,
            createURL: URLs.JOB_CREATE,
            updateURL: URLs.JOB_UPDATE,
            deleteURL: URLs.JOB_DELETE
        },

        schedule: {
            view: "",
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        resource: {
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        permission: {
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        activity: {
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        contact: {
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        }

    },

    getters: {
        getList: state => (resource, callback) => {
            return callback === undefined ? state[resource].list : state[resource].list.filter(callback);
        },

        getScheduleEvents: state => callback => {
            let list = callback === undefined ? state.schedule.list : state.schedule.list.filter(callback);
            if (list.length == 0 || state.schedule.view === "") return [];
            switch (state.schedule.view) {
                case 'job':
                    return list.map(obj => {
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
                    return list.map(obj => {
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
                    throw new Error('Invalid schedule view type.');
            }
        },

        getScheduleResources: state => callback => {
            let list = [];
            if (state.schedule.view === "") return [];
            switch (state.schedule.view) {
                case 'job':
                    list = callback === undefined ? state.job.list : state.job.list.filter(callback);
                    if (list.length == 0) return [];
                    return list.map(obj => {
                        return { id: obj.id, name: obj.title };
                    });
                case 'user':
                    list = callback === undefined ? state.user.list : state.user.list.filter(callback);
                    if (list.length == 0) return [];
                    return list.map(obj => {
                        let meta = JSON.parse(obj.meta);
                        return { id: obj.id, name: obj.name, avatar: meta.avatar };
                    });
                default:
                    throw new Error('Invalid schedule view type.');
            }
        }
    },

    mutations: {

        [MUTATIONS.RESOURCE_LIST](state, payload) {
            state[payload.resource].list = payload.data;
        },

        [MUTATIONS.RESOURCE_CREATE](state, payload) {
            if (Array.isArray(payload.data))
                state[payload.resource].list.push(payload.data[0]);
            else
                state[payload.resource].list.push(payload.data);

        },

        [MUTATIONS.RESOURCE_UPDATE](state, payload) {
            let index =
                state[payload.resource].list.findIndex((el) => {
                    return el.id === payload.id;
                });
            state[payload.resource].list[index] =
                Object.assign(state[payload.resource].list[index], payload.data);
        },

        [MUTATIONS.RESOURCE_DELETE](state, payload) {
            let index =
                state[payload.resource].list.findIndex((el) => {
                    return el.id === payload.id;
                });
            state[payload.resource].list.splice(index, 1);
        },

        [MUTATIONS.RESOURCE_SCHEDULE_VIEW](state, payload) {
            if (["user", "job"].indexOf(payload) === -1)
                throw new Error("Invalid schedule view.");
            state.schedule.view = payload;
        }

    },

    actions: {
        list({ state, commit, dispatch }, { resource, query }) {
            dispatch("checkResource", resource);
            return new Promise((resolve, reject) => {
                api.get(state[resource].listURL, query)
                    .then(respond => {
                        commit(MUTATIONS.RESOURCE_LIST, { resource: resource, data: respond.data });
                        resolve(respond.data);
                    }).catch(error => {
                        reject(error);
                    });
            });
        },

        create({ state, commit, dispatch }, { resource, data }) {
            dispatch("checkResource", resource);
            return new Promise((resolve, reject) => {
                api.post(state[resource].createURL, data)
                    .then(respond => {
                        commit(MUTATIONS.RESOURCE_CREATE, { resource: resource, data: respond.data });
                        resolve(respond.data);
                    }).catch(error => {
                        reject(error);
                    });
            });
        },

        update({ state, commit, dispatch }, { resource, id, data }) {
            dispatch("checkResource", resource);
            return new Promise((resolve, reject) => {
                api.post(`${state[resource].updateURL}/${id}`, data)
                    .then(respond => {
                        commit(MUTATIONS.RESOURCE_UPDATE, { resource: resource, id: id, data: respond.data });
                        resolve(respond.data);
                    }).catch(error => {
                        reject(error);
                    });
            });
        },

        delete({ state, commit, dispatch }, { resource, id }) {
            dispatch("checkResource", resource);
            return new Promise((resolve, reject) => {
                api.post(`${state[resource].deleteURL}/${id}`)
                    .then(respond => {
                        commit(MUTATIONS.RESOURCE_DELETE, { resource: resource, id: id });
                        resolve(respond.data);
                    }).catch(error => {
                        reject(error);
                    });
            });
        },

        scheduleInit({ commit, dispatch }, view) {
            return new Promise((resolve, reject) => {
                commit(MUTATIONS.RESOURCE_SCHEDULE_VIEW, view);
                dispatch("list", { resource: "schedule" }).then(() => {
                    dispatch("list", { resource: view }).then(() => {
                        resolve();
                    }).catch(error => reject(error));
                }).catch(error => reject(error));
            });
        },

        checkResource({ state }, payload) {
            if (["user", "job", "schedule", "request", "activity"].indexOf(payload) === -1)
                throw new Error("Invalid resource name.");
        }
    }
}