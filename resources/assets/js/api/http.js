
import axios from 'axios';
import { store } from '../store/store';

export const api = {
    get({ url, query = null, data = null, responseType = "json", isJson = true, isAuth = true }) {
        return makeRequest({
            method: 'GET',
            url: url,
            query: query,
            isJson: isJson,
            isAuth: isAuth,
            data: data,
            responseType: responseType
        });
    },

    post({ url, data = null, query = [], responseType = "json", isJson = true, isAuth = true, hasAttachments = false }) {
        return makeRequest({
            method: 'POST',
            url: url,
            query: query,
            isJson: isJson,
            isAuth: isAuth,
            hasAttachments: hasAttachments,
            responseType: responseType,
            data: data
        });
    },

    list({ url, query = [], method = 'GET', isJson = true, isAuth = true, data = null }) {
        return makeRequest({
            method: method,
            url: url,
            query: query,
            isJson: isJson,
            isAuth: isAuth,
            data: data
        });
    },

    create({ url, data = null, query = [], method = 'POST', isJson = true, isAuth = true, hasAttachments = false }) {
        return makeRequest({
            method: method,
            url: url,
            query: query,
            isJson: isJson,
            isAuth: isAuth,
            hasAttachments: hasAttachments,
            data: data
        });
    },

    update({ url, data = null, query = [], method = 'POST', isJson = true, isAuth = true, hasAttachments = false }) {
        return makeRequest({
            method: method,
            url: url,
            query: query,
            isJson: isJson,
            isAuth: isAuth,
            hasAttachments: hasAttachments,
            data: data
        });
    },

    delete({ url, data = null, query = [], method = 'POST', isJson = true, isAuth = true }) {
        return makeRequest({
            method: method,
            url: url,
            query: query,
            isJson: isJson,
            isAuth: isAuth,
            data: data
        });
    }
}

/**
 * Create an axios request. 
 * @param {String}   method   request method
 * @param {String}   url      base URL
 * @param {object}   query    request query string
 * @param {Boolean}  isJson   is a json request
 * @param {Boolean}  isAuth   is an authenticated request
 * @param {object}   data     request data
 * @returns {object} axios
 */
function makeRequest({
    method, url,
    query = null,
    isJson = true,
    isAuth = false,
    hasAttachments = false,
    responseType = "json",
    data = null }) {

    let slug = url;
    let header = {};
    let token = store.state.user.token.value;

    if (query !== null) {
        if (typeof (query) === 'object')
            Object.keys(query).forEach((key, index) => {
                if (index == 0)
                    slug += '?' + key + '=' + query[key];
                else
                    slug += '&' + key + '=' + query[key];
            });
        else if (typeof (query) === 'string')
            slug += query;
    }

    console.log(slug);

    if (isJson) {
        header['Accept'] = 'application/json';
    }

    if (isJson && method === "POST" && !hasAttachments) {
        header['Content-Type'] = 'application/json';
    }

    if (hasAttachments) {
        header['Content-Type'] = 'multipart/form-data';
    }

    if (isAuth) {
        header['Authorization'] = `Bearer ${token}`;
    }

    let req = axios({
        method: method,
        url: slug,
        data: data,
        headers: header,
        responseType: responseType
    });

    return req;
}