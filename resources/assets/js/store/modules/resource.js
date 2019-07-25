import { api } from "../../api/http";
import * as URLs from "../../api/resources";
import * as MUTATIONS from "../mutation-types";
import Notification from '../../utils/notification';

class Resource {
    constructor(name, listUrl, showUrl, createUrl, updateUrl, deleteUrl) {
        this.data = [];
        this.query = "";
        this.name = name.toLowerCase();
        this.listUrl = listUrl;
        this.showUrl = showUrl;
        this.createUrl = createUrl;
        this.updateUrl = updateUrl;
        this.deleteUrl = deleteUrl;
    }

    getQuery() {
        return this.query;
    }

    setQuery(value) {
        this.query = value;
    }

    getData() {
        return this.data;
    }

    setData(value) {
        this.data = value;
    }
}

const resourceList = [
    "user",
    "role",
    "job",
    "form",
    "team",
    "contact",
    "comment",
    "request",
    "activity",
    "schedule",
    "timesheet",
];

export const resourceModule = {
    namespaced: true,

    state: {
        resources: initResources(),
    },

    getters: {
        getList: state => (resName, callback) => {
            let res = state.resources.find(item => item.name === resName);
            if (!res.data) return [];
            return callback === undefined ? res.getData() : res.getData().filter(callback);
        }
    },

    mutations: {
        [MUTATIONS.RESOURCE_QUERY](state, payload) {
            payload.resource.query = payload.query;
        },

        [MUTATIONS.RESOURCE_UPDATE](state, payload) {
            payload.resource.setData(payload.data);
        },

        [MUTATIONS.RESOURCE_CLEAR](state, payload) {
            payload.resource.setQuery('');
            payload.resource.setData([]);
        },
    },

    actions: {        
        list({ state, commit, dispatch }, { resource, query }) {
            dispatch("checkResource", resource);
            let res = state.resources.find(item => item.name === resource);
            return new Promise((resolve, reject) => {
                api.get({ url: res.listUrl, query: query })
                    .then(respond => {
                        let data = null;
                        if (respond.data.per_page)
                            data = respond.data.data;
                        else
                            data = respond.data;
                        commit(MUTATIONS.RESOURCE_QUERY, { resource: res, query: query });
                        commit(MUTATIONS.RESOURCE_UPDATE, { resource: res, data: data });
                        resolve(respond.data);
                    }).catch(error => {
                        console.log(error);
                        dispatch('handleError', error);
                        reject(error);
                    });
            });
        },

        create({ state, dispatch }, { resource, data, hasAttachments = false }) {
            dispatch("checkResource", resource);
            let res = state.resources.find(item => item.name === resource);
            return new Promise((resolve, reject) => {
                api.create({ url: res.createUrl, data: data, hasAttachments: hasAttachments })
                    .then(respond => {
                        resolve(respond.data);
                    }).catch(error => {
                        console.log(error);
                        dispatch('handleError', error);
                        reject(error);
                    });
            });
        },

        show({ state, dispatch }, { resource, id, query }) {
            dispatch("checkResource", resource);
            let res = state.resources.find(item => item.name === resource);
            return new Promise((resolve, reject) => {
                api.get({ url: `${res.showUrl}/${id}`, query: query })
                    .then(respond => {
                        resolve(respond.data);
                    }).catch(error => {
                        console.log(error);
                        dispatch('handleError', error);
                        reject(error);
                    });
            });
        },

        update({ state, dispatch }, { resource, id, data, method = "PUT", hasAttachments = false }) {
            dispatch("checkResource", resource);
            let res = state.resources.find(item => item.name === resource);
            return new Promise((resolve, reject) => {
                api.update({ url: `${res.updateUrl}/${id}`, data: data, method:method, hasAttachments: hasAttachments })
                    .then(respond => {
                        resolve(respond.data);
                    }).catch(error => {
                        console.log(error);
                        dispatch('handleError', error);
                        reject(error);
                    });
            });
        },

        delete({ state, dispatch }, { resource, id }) {
            dispatch("checkResource", resource);
            let res = state.resources.find(item => item.name === resource);
            return new Promise((resolve, reject) => {
                api.delete({ url: `${res.deleteUrl}/${id}` })
                    .then(respond => {
                        resolve(respond.data);
                    }).catch(error => {
                        console.log(error);
                        dispatch('handleError', error);
                        reject(error);
                    });
            });
        },

        onServerUpdate({ state, dispatch }, resource) {
            dispatch("checkResource", resource);
            let res = state.resources.find(item => item.name === resource);
            dispatch('list', { resource: res.name, query: res.query });
        },

        clearResource({ state, commit, dispatch }, resource) {
            dispatch("checkResource", resource);
            let res = state.resources.find(item => item.name === resource);
            commit(MUTATIONS.RESOURCE_CLEAR, { resource: res });
        },

        checkResource({ state }, resource) {
            if (resourceList.indexOf(resource) === -1)
                throw new Error(`Invalid resource name: ${resource}`);
        },

        handleError({ dispatch }, req) {
            let msg = '';
            let type = 'error';
            let title = 'Whoops';
            let autohide = false;
            let error = req.response.data;

            // Notify user about the error.
            switch (error.code) {
                case 1002:
                    type = 'warning';
                    title = 'Auth Error';
                    msg = 'You are not logged in, please logout and login again.';
                    break;
                case 1003:
                    type = 'warning';
                    title = 'Permission Error';
                    msg = 'You are not allowed to do this action, please check your permissions.';
                    break;
                case 1004:
                    msg = 'The data is invalid or in a bad format, please try again.';
                    break;
                case 1005:
                    msg = 'Server side error, something went wrong while processing your request, please try again later.';
                    break;
                default:
                    msg = "Something went wrong!!!";
                    break;
            }

            dispatch('core/notify', new Notification({
                type: type,
                title: title,
                message: msg,
                autoHide: autohide
            }), { root: true });
        }
    }
}

function initResources() {
    let list = [];
    resourceList.forEach(res => {
        res = res.toUpperCase();
        list.push(new Resource(
            res,
            URLs[`${res}_LIST`],
            URLs[`${res}_SHOW`],
            URLs[`${res}_CREATE`],
            URLs[`${res}_UPDATE`],
            URLs[`${res}_DELETE`]
        ));
    });
    return list;
}