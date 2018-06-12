
import { job } from '../../api/http';
import {
    JOB_LIST,
} from '../mutation-types';

export const jobModule = {
    namespaced: true,
    state: {
        list: [],
        selected: [],
    },
    getters: {
        getJobList: (state) => {
            return state.list;
        },        
    },
    mutations: {
        [JOB_LIST](state, payload) {
            state.list = payload;
        }
    },
    actions: {
        list({ state, commit }, { query }) {
            console.log(query);            
            return new Promise((resolve, reject) => {
                job.list(query)
                    .then(respond => {
                        if (respond.status == 200) {
                            commit(JOB_LIST, respond.data);
                            resolve(respond.data);
                        } else {
                            reject(respond.data);
                        }
                    }).catch(error => {
                        console.log(error);
                    });
            });
        }
    }
} 