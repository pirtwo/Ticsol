
import axios from 'axios';
import { store } from '../store/store';
import * as resources from './resources';

export const auth = {

    login(credentials) {
        return makeRequest(
            'POST',
            resources.AUTH_LOGIN,
            [], true, false, { username: credentials.username, password: credentials.password });
    },

    register(credentials) {

    },

    reset(credentials) {

    },

    logout(credentials) {
        return makeRequest('POST', resources.AUTH_LOGOUT, [], true, true);
    },

}

export const user = {
    list(query = []) {
        return makeRequest('GET', resources.USER_LIST, query, true, true);
    }
}

export const job = {
    list(query = []) {
        return makeRequest('GET', resources.JOB_LIST, query, true, true);
    },
    create(data) {
        return makeRequest('POST', resources.JOB_CREATE, [], true, true, data);
    },
    update(data) {
        return makeRequest('POST', resources.JOB_UPDATE, [], true, true, data);
    }
}

export const schedule = {
    list(query = []) {
        return makeRequest('GET', resources.SCHEDULE_LIST, query, true, true);
    },

    create(data) {
        return makeRequest('POST', resources.SCHEDULE_CREATE, [], true, true, data);
    },

    update(data) {
        return makeRequest('POST', resources.SCHEDULE_UPDATE, [], true, true, data);
    }
}

export const api = {
    get(url, query = [], data = null, isJson = true, isAuth = true){
        return makeRequest('GET', url, query, isJson, isAuth, data);
    },

    post(url, data = null, query = [], isJson = true, isAuth = true){
        return makeRequest('POST', url, query, isJson, isAuth, data);
    },

    list(url, query = [], method = 'GET', isJson = true, isAuth = true, data = null) {
        return makeRequest(method, url, query, isJson, isAuth, data);
    },

    create(url, data = null, query = [], method = 'POST', isJson = true, isAuth = true){
        return makeRequest(method, url, query, isJson, isAuth, data);
    },

    update(url, data = null, query = [], method = 'POST', isJson = true, isAuth = true){
        return makeRequest(method, url, query, isJson, isAuth, data);
    },

    delete(url, data = null, query = [], method = 'POST', isJson = true, isAuth = true){
        return makeRequest(method, url, query, isJson, isAuth, data);
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
        header['Accept'] = 'application/json';        
    }
    
    if(isJson && method === "POST"){
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