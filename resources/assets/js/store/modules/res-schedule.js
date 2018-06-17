
import {  } from '../../api/http';
import {
    
} from '../mutation-types';

export const scheduleModule = {
    namespaced: true,
    state: {
        events: [],
        resources: [],    
        resourceType: 'job',    
    },
    getters: {
        
    },
    mutations: {
        
    },
    actions: {
        resourceType(){
            //change scheduler resource type
        },

        resourceList(){

        }, 

        eventList(){

        },

        eventCreate(){

        },

        eventUpdate(){

        },

        eventDelete(){

        }
    }
} 