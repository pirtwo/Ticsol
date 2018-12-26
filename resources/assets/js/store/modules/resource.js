import { api } from "../../api/http";
import * as URLs from "../../api/resources";
import * as MUTATIONS from "../mutation-types";

const resourceList = [
    "user",
    "job",
    "schedule",
    "timesheet",
    "request",
    "activity",
    "form",
    "contact",
    "comment",
    "role"];

export const resourceModule = {
    namespaced: true,

    state: {
        user: {
            list: [],
            listURL: URLs.USER_LIST,
            showURL: URLs.USER_SHOW,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        role: {
            list: [],
            listURL: URLs.ROLE_LIST,
            showURL: URLs.ROLE_SHOW,
            createURL: URLs.ROLE_CREATE,
            updateURL: URLs.ROLE_UPDATE,
            deleteURL: URLs.ROLE_DELETE
        },

        request: {
            list: [],
            listURL: URLs.REQUEST_LIST,
            showURL: URLs.REQUEST_SHOW,
            createURL: URLs.REQUEST_CREATE,
            updateURL: URLs.REQUEST_UPDATE,
            deleteURL: URLs.REQUEST_DELETE
        },

        job: {
            list: [],
            listURL: URLs.JOB_LIST,
            showURL: URLs.JOB_SHOW,
            createURL: URLs.JOB_CREATE,
            updateURL: URLs.JOB_UPDATE,
            deleteURL: URLs.JOB_DELETE
        },

        schedule: {
            view: "",
            list: [],
            listURL: URLs.SCHEDULE_LIST,
            showURL: URLs.SCHEDULE_SHOW,
            createURL: URLs.SCHEDULE_CREATE,
            updateURL: URLs.SCHEDULE_UPDATE,
            deleteURL: URLs.SCHEDULE_DELETE
        },

        timesheet: {
            list: [],
            listURL: URLs.TIMESHEET_LIST,
            showURL: URLs.TIMESHEET_SHOW,
            createURL: URLs.TIMESHEET_CREATE,
            updateURL: URLs.TIMESHEET_UPDATE,
            deleteURL: URLs.TIMESHEET_DELETE
        },

        activity: {
            list: [],
            listURL: URLs.ACTIVITY_LIST,
            showURL: URLs.ACTIVITY_SHOW,
            createURL: URLs.ACTIVITY_CREATE,
            updateURL: URLs.ACTIVITY_UPDATE,
            deleteURL: URLs.ACTIVITY_DELETE
        },

        contact: {
            list: [],
            listURL: URLs.CONTACT_LIST,
            showURL: URLs.CONTACT_SHOW,
            createURL: URLs.CONTACT_CREATE,
            updateURL: URLs.CONTACT_UPDATE,
            deleteURL: URLs.CONTACT_DELETE
        },

        form: {
            list: [],
            listURL: URLs.FORM_LIST,
            showURL: URLs.FORM_SHOW,
            createURL: URLs.FORM_CREATE,
            updateURL: URLs.FORM_UPDATE,
            deleteURL: URLs.FORM_DELETE
        },

        comment: {
            list: [],
            listURL: URLs.COMMENT_LIST,
            createURL: URLs.COMMENT_CREATE,
            updateURL: URLs.COMMENT_UPDATE,
            deleteURL: URLs.COMMENT_DELETE
        },

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
                            text: obj.user.name
                        };
                    });
                case 'user':
                    return list.map(obj => {
                        return {
                            id: obj.id,
                            resource: obj.user_id,
                            start: obj.start,
                            end: obj.end,
                            text: obj.job.title
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
                        return { id: obj.id, name: obj.title, code: obj.code };
                    });
                case 'user':
                    list = callback === undefined ? state.user.list : state.user.list.filter(callback);
                    if (list.length == 0) return [];
                    return list.map(obj => {
                        return { id: obj.id, name: obj.name, avatar: obj.meta.avatar };
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

        [MUTATIONS.RESOURCE_LIST_CLEAR](state, payload) {
            state[payload].list = [];
        },

        [MUTATIONS.RESOURCE_CREATE](state, payload) {
            // if (Array.isArray(payload.data))
            //     state[payload.resource].list.push(payload.data[0]);            
            // else
            //     state[payload.resource].list.push(payload.data);
        },

        [MUTATIONS.RESOURCE_UPDATE](state, payload) {
            if (state[payload.resource].list.length === 0)
                return;
            let index =
                state[payload.resource].list.findIndex(item => item.id == payload.id);
            if (index > -1) {
                state[payload.resource].list[index] =
                    Object.assign(state[payload.resource].list[index], payload.data);
            } else
                throw new Error('Can not find the item');
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
                        let data = null;
                        if (query !== undefined && query.page !== undefined)
                            data = respond.data.data;
                        else
                            data = respond.data;
                        commit(MUTATIONS.RESOURCE_LIST, { resource: resource, data: data });
                        resolve(respond.data);
                    }).catch(error => {
                        console.log(error);
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
                        console.log(error);
                        reject(error);
                    });
            });
        },

        show({ state, dispatch }, { resource, id, query }) {
            dispatch("checkResource", resource);
            return new Promise((resolve, reject) => {
                api.get(`${state[resource].showURL}/${id}`, query)
                    .then(respond => {
                        resolve(respond.data);
                    }).catch(error => {
                        console.log(error);
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
                        console.log(error);
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
                        console.log(error);
                        reject(error);
                    });
            });
        },

        clearList({ commit, dispatch }, resource) {
            dispatch("checkResource", resource);
            commit(MUTATIONS.RESOURCE_LIST_CLEAR, resource);
        },

        scheduleInit({ commit, dispatch }, view) {
            return new Promise((resolve, reject) => {
                commit(MUTATIONS.RESOURCE_SCHEDULE_VIEW, view);
                let events = dispatch("list", { resource: "schedule", query: { with: "user,job" } });
                let resources = dispatch("list", { resource: view });
                Promise.all([events, resources])
                    .then(() => resolve())
                    .catch(error => {
                        console.log(error);
                        reject(error);
                    });
            });
        },

        scheduleView({ commit }, view) {
            commit(MUTATIONS.RESOURCE_SCHEDULE_VIEW, view);
        },

        checkResource({ state }, payload) {
            if (resourceList.indexOf(payload) === -1)
                throw new Error("Invalid resource name.");
        }
    }
}