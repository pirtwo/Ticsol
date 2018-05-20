import Vue from 'vue';
import VueRouter from 'vue-router';
import {store} from './store/store.js';

Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'auth',
            redirect: { name: 'login' },
            meta: {requireAuth: false},
            component: require('./components/pages/auth/Auth.vue'),            
            children: [
                {
                    path: 'login',
                    name: 'login',
                    meta: {requireAuth: false},
                    component: require('./components/pages/auth/Login.vue')
                },
                {
                    path: 'logout',
                    name: 'logout',
                    meta: {requireAuth: false},
                    component: require('./components/pages/auth/Logout.vue')
                },
                {
                    path: 'register',
                    name: 'register',
                    meta: {requireAuth: false},
                    component: require('./components/pages/auth/Register.vue')
                },
                {
                    path: 'resetpassword',
                    name: 'resetpassword',
                    meta: {requireAuth: false},
                    component: require('./components/pages/auth/ResetPassword.vue')
                },
            ]
        },
        {
            path: '/home',
            name: 'home',
            meta: {requireAuth: true},
            component: require('./components/pages/home/Home.vue')
        },
        {
            path: '/dash',
            name: 'dash',
            meta: {requireAuth: true},
            component: require('./components/pages/dashboard/Dashboard.vue'),
            children: [
                {
                    path: 'inbox',
                    name: 'inbox',
                    meta: {requireAuth: true},
                    component: require('./components/pages/dashboard/Inbox.vue')
                },
                {
                    path: 'hr',
                    name: 'hr',
                    meta: {requireAuth: true},
                    component: require('./components/pages/dashboard/HR.vue')
                },
                {
                    path: 'requests',
                    name: 'requests',
                    meta: {requireAuth: true},
                    component: require('./components/pages/dashboard/Requests.vue')
                },
                {
                    path: 'schedule',
                    name: 'schedule',
                    meta: {requireAuth: true},
                    component: require('./components/pages/dashboard/Schedule.vue')
                },
                {
                    path: 'timesheets',
                    name: 'timesheets',
                    meta: {requireAuth: true},
                    component: require('./components/pages/dashboard/TimeSheets.vue')
                }
            ]
        }
    ]
});

router.beforeEach((to, from, next) => {
    console.log(`From: ${from.path}`);
    console.log(`To: ${to.path}`);
    console.log(`isAuth: ${store.state.user.isAuth}`);
    
    var user = store.state.user;
    if (to.meta.requireAuth === true && user.isAuth === false ) {
        next('/');
    } else {
        next();
    }
});