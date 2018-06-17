
import { auth } from '../../api/http';
import {
    USER_OBJECT,
} from '../mutation-types';

export const userModule = {
    namespaced: true,
    state: {
        id: '',
        name: '',
        role: '',
        avatar: '',
    },
    getters: {
        getUserName: (state) => {
            return state.name;
        },
        getUserRole: (state) => {
            return state.role;
        },
        getUserAvatar: (state) => {
            return state.avatar;
        },        
    },
    mutations: {
        [USER_OBJECT](state, payload){
            state.id = payload.id;
            state.name = payload.username;
            state.role = payload.role;
            state.avatar = payload.avatar;
        }
    },
    actions: {
        
    }
} 