
import axios from 'axios';
import { store } from '../store/store';
import * as resources from './resources';

export const auth = {

    login(credentials) {
        console.log("request to:" + resources.LOGIN_URL);
        return axios(
            {
                method: 'POST',
                url: resources.LOGIN_URL,
                data: {
                    username: credentials.username,
                    password: credentials.password,
                },
                config: { headers: { Accept: 'application/json', } }
            });
    },

    register(credentials) {

    },

    reset(credentials) {

    },

    logout(credentials) {
        console.log("request to:" + resources.LOGOUT_URL);
        return axios(
            {
                method: 'POST',
                url: resources.LOGOUT_URL,
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${credentials.token}`
                }
            });
    },

}

export const user = {
    list() {
        return axios({
            method: 'GET',
            url: resources.USER_LIST_URL,
            config: { headers: { Accept: 'application/json', } }
        });
    }
}

export const job = {
    list(query) {
        return makeRequest('GET', resources.JOB_LIST_URL, query, true, true);
    },
    create(data) {
        return makeRequest('POST', resources.JOB_CREATE_URL, [], true, true, data);
    }
}

/**
 * 
 * @param {String}   method   request method
 * @param {String}   url      base URL
 * @param {Array}    query    request query string
 * @param {Boolean}  isJson   is a json request
 * @param {Boolean}  isAuth   is an authenticated request
 * @param {object}   data     request data
 */
function makeRequest(method, url, query = [], isJson = true, isAuth = false, data = null) {
    let slug = url;
    let header = {};
    let token = store.state.auth.token.value;

    if (query.length !== 0) {
        query.forEach((obj, index) => {
            if (index == 0)
                slug += '?' + obj.key + '=' + obj.value;
            else
                slug += '&' + obj.key + '=' + obj.value;
        });
    }
    if (isJson) {
        header.Accept = 'application/json';
    }
    if (isAuth) {
        header.Authorization = `Bearer ${token}`;
    }

    let req = axios({
        method: method,
        url: slug,
        data: data,
        headers: header
    });
    console.log(slug);
    return req;
}