
import axios from 'axios';
import { store } from '../store/store';

export const api = {
    get(url, query = null, data = null, isJson = true, isAuth = true) {
        return makeRequest('GET', url, query, isJson, isAuth, data);
    },

    post(url, data = null, query = [], isJson = true, isAuth = true) {
        return makeRequest('POST', url, query, isJson, isAuth, data);
    },

    list(url, query = [], method = 'GET', isJson = true, isAuth = true, data = null) {
        return makeRequest(method, url, query, isJson, isAuth, data);
    },

    create(url, data = null, query = [], method = 'POST', isJson = true, isAuth = true) {
        return makeRequest(method, url, query, isJson, isAuth, data);
    },

    update(url, data = null, query = [], method = 'POST', isJson = true, isAuth = true) {
        return makeRequest(method, url, query, isJson, isAuth, data);
    },

    delete(url, data = null, query = [], method = 'POST', isJson = true, isAuth = true) {
        return makeRequest(method, url, query, isJson, isAuth, data);
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
function makeRequest(method, url, query = null, isJson = true, isAuth = false, data = null) {
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

    if (isJson && method === "POST") {
        header['Content-Type'] = 'application/json';
    }

    if (isAuth) {
        header['Authorization'] = `Bearer ${token}`;
    }

    let req = axios({
        method: method,
        url: slug,
        data: data,
        headers: header
    });

    return req;
}