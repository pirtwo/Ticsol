
import auth from '../../api/auth';
import {
    USER_OBJECT,
    USRE_TOKKEN,
    USER_AUTHENTICATED,
    USER_NOTAUTHENTICATED
} from '../mutation-types';

export const userModule = {
    namespaced: true,
    state: {
        isAuth:
            localStorage.isAuth !== undefined ? localStorage.isAuth : false,
        user: {
            id:
                localStorage.userId !== undefined ? localStorage.userId : null,
            name:
                localStorage.username !== undefined ? localStorage.username : 'Guest',
            avatar:
                localStorage.avatar !== undefined ? localStorage.avatar : null,
        },
        token: {
            value:
                localStorage.tokenValue !== undefined ? localStorage.tokenValue : null,
            scope:
                localStorage.tokenScope !== undefined ? localStorage.tokenScope : null,
            expire:
                localStorage.tokenExpire !== undefined ? localStorage.tokenExpire : null,
            issued:
                localStorage.tokenIssued !== undefined ? localStorage.tokenIssued : null,
        }
    },
    getters: {

    },
    mutations: {

        [USER_OBJECT](state, payload) {
            state.user.id = payload.userId;
            state.user.name = payload.username;
            state.user.avatar = payload.userAvatar;
        },

        [USRE_TOKKEN](state, payload) {
            state.token.value = payload.value;
            state.token.scope = payload.scope;
            state.token.expire = payload.expire;
            state.token.issued = payload.issued;
        },

        [USER_AUTHENTICATED](state) {
            state.isAuth = true;
        },

        [USER_NOTAUTHENTICATED](state) {
            state.isAuth = false;
        },

    },
    actions: {

        login({ state, commit }, payload) {
            return new Promise((resolve, reject) => {
                auth.login(payload).then(respond => {
                    if (respond.status === 201) {
                        commit(USRE_TOKKEN, respond.data);
                        commit(USER_AUTHENTICATED);
                        resolve('You logged in successfuly.');
                    } else {
                        commit(USER_NOTAUTHENTICATED);
                        console.log('reject');
                        reject(respond);
                    }
                }).catch(error => {
                    console.log('error');
                    reject(error.response.data);                    
                });
            });
        },

        logout({ state, commit }, payload) {
            auth.logout(payload).then(respond => {
                if (respond.status === 201) {
                    commit(USER_NOTAUTHENTICATED);
                }
            });
        },
    }
} 