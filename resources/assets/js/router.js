import Vue from 'vue';
import VueRouter from 'vue-router';
import { store } from './store/store.js';

Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'auth',
            redirect: { name: 'login' },
            meta: { requireAuth: false },
            component: require('./components/pages/auth/Auth.vue'),
            children: [
                {
                    path: 'login',
                    name: 'login',
                    meta: { requireAuth: false },
                    component: require('./components/pages/auth/Login.vue')
                },
                {
                    path: 'logout',
                    name: 'logout',
                    meta: { requireAuth: false },
                    component: require('./components/pages/auth/Logout.vue')
                },
                {
                    path: 'register',
                    name: 'register',
                    meta: { requireAuth: false },
                    component: require('./components/pages/auth/Register.vue')
                },
                {
                    path: 'resetpassword',
                    name: 'resetpassword',
                    meta: { requireAuth: false },
                    component: require('./components/pages/auth/ResetPassword.vue')
                },
            ]
        },
        {
            path: '/dash',
            name: 'dash',
            meta: { requireAuth: true },
            redirect: { name: 'home' },
            component: require('./components/pages/dashboard/Dashboard.vue'),
            children: [
                {
                    path: '/home',
                    name: 'home',
                    meta: { requireAuth: true },
                    component: require('./components/pages/dashboard/Home.vue')
                },
                {
                    path: '/inbox',
                    name: 'inbox',
                    meta: { requireAuth: true },
                    redirect: { name: 'inboxList' },
                    component: require('./components/pages/dashboard/inbox/Inbox.vue'),
                    children: [
                        {
                            path: 'list',
                            name: 'inboxList',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/inbox/inboxList.vue'),
                        }
                    ]
                },
                {
                    path: '/hr',
                    name: 'hr',
                    meta: { requireAuth: true },
                    component: require('./components/pages/dashboard/HRs/HRs.vue'),
                    children: [

                    ]
                },
                {
                    path: '/requests',
                    name: 'requests',
                    meta: { requireAuth: true },
                    component: require('./components/pages/dashboard/requests/Requests.vue'),
                    children: [

                    ]
                },
                {
                    path: '/schedules',
                    name: 'schedule',
                    meta: { requireAuth: true },
                    redirect: { name: 'scheduler' },
                    component: require('./components/pages/dashboard/schedules/Schedules.vue'),
                    children: [
                        {
                            path: '/scheduler',
                            name: 'scheduler',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/schedules/Scheduler.vue'),
                        }
                    ]
                },
                {
                    path: '/timesheets',
                    name: 'timesheets',
                    meta: { requireAuth: true },
                    component: require('./components/pages/dashboard/timesheets/TimeSheets.vue'),
                    children: [

                    ]
                },
                {
                    path: '/jobs',
                    name: 'jobs',
                    meta: { requireAuth: true },
                    redirect: { name: 'jobList' },
                    component: require('./components/pages/dashboard/jobs/jobs.vue'),
                    children: [
                        {
                            path: 'list',
                            name: 'jobList',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobList.vue'),
                        },
                        {
                            path: 'view',
                            name: 'jobView',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobView.vue'),
                        },
                        {
                            path: 'profile',
                            name: 'jobProfile',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobProfile.vue'),
                        }
                    ]
                }
            ]
        },

    ]
});

// router.beforeEach((to, from, next) => {
//     var state = store.state.auth;
//     if (to.meta.requireAuth === true && state.isAuth === false) {
//         next('/');
//     } else {
//         next();
//     }
//     next();
// });