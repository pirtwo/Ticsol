import Vue from 'vue';
import VueRouter from 'vue-router';
import { store } from './store/store.js';

Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [

        // Auth 
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

        // Dashboard
        {
            path: '/dash',
            name: 'dash',
            meta: { requireAuth: true },
            redirect: { name: 'home' },
            component: require('./components/pages/dashboard/Dashboard.vue'),
            children: [

                // Home
                {
                    path: '/home',
                    name: 'home',
                    meta: { requireAuth: true },
                    component: require('./components/pages/dashboard/Home.vue')
                },

                // Inbox & Requests 
                {
                    path: '/request',
                    name: 'request',
                    meta: { requireAuth: true },
                    redirect: { name: 'inbox' },
                    component: require('./components/pages/dashboard/requests/Requests.vue'),
                    children: [
                        {
                            props: true,
                            name: 'inbox',
                            meta: { requireAuth: true },   
                            path: '/inbox/:col?/:opt?/:val?',                                                     
                            component: require('./components/pages/dashboard/requests/RequestList.vue'),
                        },
                        {
                            props: true,
                            name: 'reqLeave',
                            meta: { requireAuth: true },
                            path: 'leave/:id?',
                            component: require('./components/pages/dashboard/requests/LeaveRequest.vue'),
                        },
                        {
                            props: true,
                            name: 'reqReimb',
                            meta: { requireAuth: true },
                            path: 'reimbursement/:id?',
                            component: require('./components/pages/dashboard/requests/ReimbRequest.vue'),
                        }
                    ]
                },

                // HR
                {
                    path: '/hr',
                    name: 'hr',
                    meta: { requireAuth: true },
                    component: require('./components/pages/dashboard/HRs/HRs.vue'),
                    children: [

                    ]
                },

                // Schedule
                {
                    path: '/schedule',
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

                // Timesheets
                {
                    path: '/timesheet',
                    name: 'timesheet',
                    meta: { requireAuth: true },
                    redirect: { name: 'timesheetCreate' },
                    component: require('./components/pages/dashboard/timesheets/TimeSheets.vue'),
                    children: [
                        {
                            path: 'create',
                            name: 'timesheetCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/timesheets/TimesheetCreate.vue'),
                        },
                    ]
                },

                // Jobs
                {
                    path: '/job',
                    name: 'job',
                    meta: { requireAuth: true },
                    redirect: { name: 'jobList' },
                    component: require('./components/pages/dashboard/jobs/Jobs.vue'),
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'jobList',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobList.vue'),
                        },
                        {
                            path: 'create',
                            name: 'jobCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobCreate.vue'),
                        },
                        {
                            path: ':id/details',
                            name: 'jobDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobDetails.vue'),
                        },
                        {
                            path: ':id/related-jobs',
                            name: 'jobChilds',
                            props: true,
                            meta: { requireAuth: true },
                        },
                        {
                            path: ':id/contacts',
                            name: 'jobContacts',
                            props: true,
                            meta: { requireAuth: true },
                        },
                        {
                            path: ':id/scheduled-items',
                            name: 'jobItems',
                            props: true,
                            meta: { requireAuth: true },
                        },
                        {
                            path: ':id/reports',
                            name: 'jobReports',
                            props: true,
                            meta: { requireAuth: true },
                        },
                        {
                            path: ':id/requests',
                            name: 'jobRequests',
                            props: true,
                            meta: { requireAuth: true },
                        },
                    ]
                },

                // Activities
                {
                    path: '/activity',
                    name: 'activity',
                    meta: { requireAuth: true },
                    redirect: { name: 'activityList' },
                    component: require('./components/pages/dashboard/activities/Activity.vue'),
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'activityList',
                            meta: { requireAuth: true },
                            props: true,
                            component: require('./components/pages/dashboard/activities/ActivityList.vue'),
                        },
                        {
                            path: 'create',
                            name: 'activityCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/activities/ActivityCreate.vue'),
                        },
                        {
                            path: ':id/details',
                            name: 'activityDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/activities/ActivityDetails.vue'),
                        }
                    ]
                },

                // Contacts
                {
                    path: '/contact',
                    name: 'contact',
                    meta: { requireAuth: true },
                    redirect: { name: 'contactList' },
                    component: require('./components/pages/dashboard/contacts/Contacts.vue'),
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'contactList',
                            meta: { requireAuth: true },
                            props: true,
                            component: require('./components/pages/dashboard/contacts/ContactList.vue'),
                        },
                        {
                            path: 'create',
                            name: 'contactCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/contacts/ContactCreate.vue'),
                        },
                        {
                            path: ':id/details',
                            name: 'contactDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/contacts/ContactDetails.vue'),
                        }
                    ]
                },

                // Job Profiles
                {
                    path: '/profile',
                    name: 'profile',
                    meta: { requireAuth: true },
                    redirect: { name: 'profileList' },
                    component: require('./components/pages/dashboard/profiles/Profiles.vue'),
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'profileList',
                            meta: { requireAuth: true },
                            props: true,
                            component: require('./components/pages/dashboard/profiles/ProfileList.vue'),
                        },
                        {
                            path: 'create',
                            name: 'profileCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/profiles/ProfileCreate.vue'),
                        },
                        {
                            path: ':id/details',
                            name: 'profileDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/profiles/ProfileDetails.vue'),
                        }
                    ]
                },

                // Roles
                {
                    path: '/role',
                    name: 'role',
                    meta: { requireAuth: true },
                    redirect: { name: 'roleList' },
                    component: require('./components/pages/dashboard/roles/Roles.vue'),
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'roleList',
                            meta: { requireAuth: true },
                            props: true,
                            component: require('./components/pages/dashboard/roles/RoleList.vue'),
                        },
                        {
                            path: 'create',
                            name: 'roleCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/roles/RoleCreate.vue'),
                        },
                        {
                            path: ':id/details',
                            name: 'roleDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/roles/RoleDetails.vue'),
                        },
                        {
                            path: ':id/users',
                            name: 'roleUsers',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/roles/RoleUsers.vue'),
                        },
                    ]
                },

                // Comments
                {
                    path: '/comments',
                    name: 'comments',
                    meta: { requireAuth: true },
                    redirect: { name: 'roleList' },
                    component: require('./components/pages/dashboard/comments/Comments.vue'),
                    children: [
                        {
                            path: '/comments/:entity/:id',
                            name: 'commentList',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/comments/CommentList.vue'),
                        }]
                },
            ]
        },

    ]
});

// check auth
router.beforeEach((to, from, next) => {
    let state = store.state.user;
    if (to.meta.requireAuth === true && state.isAuth === false) {
        next('/');
    }
    if (to.meta.requireAuth === false && state.isAuth === true) {
        next('/home');
    } else {
        next();
    }
    next();
});