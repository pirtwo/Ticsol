
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
    list() {
        return axios({
            method: 'GET',
            url: resources.JOB_LIST_URL,
            config: { headers: { Accept: 'application/json', } }
        })
    }
}


function makeRequest(method, url, isJson = true, isAuth = false, data = null) {
    let config = {};
    let token = store.state.auth.token.value;

    config.headers.Accept =
        (isJson) ? 'application/json' : '';
    config.headers.Authorization =
        (isAuth) ? ('Bearer ' + token) : '';

    let req = axios({
        method: method,
        url: url,
        data: data,
        config: config,
    });

    return req;
}