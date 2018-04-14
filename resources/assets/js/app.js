
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
import Home from "./components/pages/home/Home.vue";
import Dashboard from "./components/pages/dashboard/Dashboard.vue";
import Inbox from "./components/pages/dashboard/Inbox.vue";
import HR from "./components/pages/dashboard/HR.vue";
import Requests from "./components/pages/dashboard/Requests.vue";
import Schedule from "./components/pages/dashboard/Schedule.vue";
import TimeSheets from "./components/pages/dashboard/TimeSheets.vue";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/dash',
            name: 'dash',
            component: Dashboard,
            children:[
                {
                    path: 'inbox',
                    component: Inbox
                },
                {
                    path: 'hr',
                    component: HR
                },
                {
                    path: 'requests',
                    component: Requests
                },
                {
                    path: 'schedule',
                    component: Schedule
                },
                {
                    path: 'timesheets',
                    component: TimeSheets
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
