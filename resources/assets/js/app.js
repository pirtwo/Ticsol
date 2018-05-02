
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import App from "./components/App.vue";

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [        
        {
            path: '/',
            name: 'auth',
            redirect: { name: 'login' },            
            component: require('./components/pages/auth/Auth.vue'),
            children:[
                {
                    path: 'login',
                    name: 'login',
                    component: require('./components/pages/auth/Login.vue')
                },
                {
                    path: 'logout',
                    name: 'logout',
                    component: require('./components/pages/auth/Logout.vue')
                },
                {
                    path: 'register',
                    name: 'register',
                    component: require('./components/pages/auth/Register.vue')
                },
                {
                    path: 'resetpassword',
                    name: 'resetpassword',
                    component: require('./components/pages/auth/ResetPassword.vue')
                },
            ]
        },
        {
            path: '/home',
            name: 'home',
            component: require('./components/pages/home/Home.vue')
        },
        {
            path: '/dash',
            name: 'dash',
            component: require('./components/pages/dashboard/Dashboard.vue'),
            children: [
                {
                    path: 'inbox',
                    name: 'inbox',
                    component: require('./components/pages/dashboard/Inbox.vue')
                },
                {
                    path: 'hr',
                    name: 'hr',
                    component: require('./components/pages/dashboard/HR.vue')
                },
                {
                    path: 'requests',
                    name: 'requests',
                    component: require('./components/pages/dashboard/Requests.vue')
                },
                {
                    path: 'schedule',
                    name: 'schedule',
                    component: require('./components/pages/dashboard/Schedule.vue')
                },
                {
                    path: 'timesheets',
                    name: 'timesheets',
                    component: require('./components/pages/dashboard/TimeSheets.vue')
                }
            ]
        }
    ]
})

const app = new Vue({
    el: '#app',
    components: { App },
    router
});
