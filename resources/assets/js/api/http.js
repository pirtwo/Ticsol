
import axios from 'axios';
import * as resources from './resources';

export const auth = {

    login(credentials) {
        console.log("Sending request to:" + resources.LOGIN_URL);
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
        return axios.post(
            resources,
            {
                headers: {
                    body: {
                        username: credentials.username,
                        email: credentials.email,
                        password: credentials.password,
                        confirm: credentials.confirm,
                        token: credentials.token,
                    }
                }
            });
    },

    reset(credentials) {
        return axios.post(
            resources.PASSWORD_RESET_URL,
            {
                headers: {
                    body: {
                        username: credentials.username,
                        email: credentials.email,
                    }
                }
            });
    },

    logout(credentials) {
        return axios.post(
            resources.LOGOUT_URL,
            {
                headers: {
                    Authorization: 'Bearer' + credentials.token
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
    list(){
        return axios({
            method: 'GET',
            url: resources.JOB_LIST_URL,
            config: { headers: { Accept: 'application/json', } }
        })
    }
}