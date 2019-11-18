import Vue from 'vue';
import VueRouter from 'vue-router';
import { store } from './store/store.js';

Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    routes: [   

        // Landing
        {
            path: '/',
            name: 'index',
            meta: { requireAuth: false },
            component: require('./components/pages/Index.vue').default,

        },        

        // Dashboard
        {
            path: '/dash',
            name: 'dash',
            meta: { requireAuth: true },
            redirect: { name: 'home' },
            component: require('./components/pages/dashboard/Dashboard.vue').default,
            beforeEnter: routerGaurd,            
            children: [

                // Home
                {
                    path: '/home',
                    name: 'home',
                    meta: { requireAuth: true },
                    component: require('./components/pages/dashboard/Home.vue').default,
                    beforeEnter: routerGaurd, 
                },

                // Inbox & Requests 
                {
                    path: '/request',
                    name: 'request',
                    meta: { requireAuth: true },
                    redirect: { name: 'inbox' },
                    component: require('./components/pages/dashboard/requests/Requests.vue').default,
                    children: [
                        {
                            props: true,
                            name: 'inbox',
                            meta: { requireAuth: true },
                            path: '/inbox/:col?/:opt?/:val?',
                            component: require('./components/pages/dashboard/requests/RequestList.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            name: 'leaveCreate',
                            meta: { requireAuth: true },
                            path: 'leave',
                            component: require('./components/pages/dashboard/requests/LeaveRequest.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            props: true,
                            name: 'leaveDetails',
                            meta: { requireAuth: true },
                            path: 'leave/:id/details',
                            component: require('./components/pages/dashboard/requests/LeaveRequest.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            name: 'reimbCreate',
                            meta: { requireAuth: true },
                            path: 'reimbursement',
                            component: require('./components/pages/dashboard/requests/ReimbRequest.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            props: true,
                            name: 'reimbDetails',
                            meta: { requireAuth: true },
                            path: 'reimbursement/:id/details',
                            component: require('./components/pages/dashboard/requests/ReimbRequest.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                    ]
                },

                // Schedule
                {
                    path: '/schedule',
                    name: 'schedule',
                    meta: { requireAuth: true },
                    redirect: { name: 'scheduler' },
                    component: require('./components/pages/dashboard/schedules/Schedules.vue').default,
                    children: [
                        {
                            path: '/scheduler',
                            name: 'scheduler',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/schedules/Scheduler.vue').default,
                            beforeEnter: routerGaurd, 
                        }
                    ]
                },

                // Team
                {
                    path: '/team',
                    name: 'team',
                    meta: { requireAuth: true },
                    redirect: { name: 'teamList' },
                    component: require('./components/pages/dashboard/team/Teams.vue').default,
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'teamList',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/team/TeamList.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'create',
                            name: 'teamCreate',
                            meta: { 
                                resourceName: 'team',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'create']
                             },
                            component: require('./components/pages/dashboard/team/TeamModify.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: ':id/details',
                            name: 'teamDetails',
                            props: true,
                            meta: { 
                                resourceName: 'team',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'update']
                             },
                            component: require('./components/pages/dashboard/team/TeamModify.vue').default,
                            beforeEnter: routerGaurd, 
                        }
                    ]
                },

                // Timesheets
                {
                    path: '/timesheet',
                    name: 'timesheet',
                    meta: { requireAuth: true },
                    redirect: { name: 'timesheetCreate' },
                    component: require('./components/pages/dashboard/timesheets/TimeSheets.vue').default,
                    children: [
                        {
                            path: 'create',
                            name: 'timesheetCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/timesheets/TimesheetModify.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: ':id/:start/:end/details',
                            name: 'timesheetDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/timesheets/TimesheetModify.vue').default,
                            beforeEnter: routerGaurd, 
                        }
                    ]
                },

                // Jobs
                {
                    path: '/job',
                    name: 'job',
                    meta: { requireAuth: true },
                    redirect: { name: 'jobList' },
                    component: require('./components/pages/dashboard/jobs/Jobs.vue').default,
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'jobList',
                            props: true,
                            meta: {
                                resourceName: 'job',
                                requireAuth: true,
                                requirePermission: false,
                                permissions: ['full', 'list']
                            },
                            component: require('./components/pages/dashboard/jobs/JobList.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'create',
                            name: 'jobCreate',
                            meta: {
                                resourceName: 'job',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'create']
                            },
                            component: require('./components/pages/dashboard/jobs/JobModify.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: ':id/details',
                            name: 'jobDetails',
                            props: true,
                            meta: {
                                resourceName: 'job',
                                requireAuth: true,
                                requirePermission: false,
                                permissions: ['full', 'update']
                            },
                            component: require('./components/pages/dashboard/jobs/JobModify.vue').default,
                            beforeEnter: routerGaurd, 
                        }
                    ]
                },

                // Activities
                {
                    path: '/activity',
                    name: 'activity',
                    meta: { requireAuth: true },
                    redirect: { name: 'activityList' },
                    component: require('./components/pages/dashboard/activities/Activity.vue').default,
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'activityList',
                            meta: { requireAuth: true },
                            props: true,
                            component: require('./components/pages/dashboard/activities/ActivityList.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'create',
                            name: 'activityCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/activities/ActivityModify.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: ':id/details',
                            name: 'activityDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/activities/ActivityModify.vue').default,
                            beforeEnter: routerGaurd, 
                        }
                    ]
                },

                // Contacts
                {
                    path: '/contact',
                    name: 'contact',
                    meta: { requireAuth: true },
                    redirect: { name: 'contactList' },
                    component: require('./components/pages/dashboard/contacts/Contacts.vue').default,
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'contactList',
                            meta: { requireAuth: true },
                            props: true,
                            component: require('./components/pages/dashboard/contacts/ContactList.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'create',
                            name: 'contactCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/contacts/ContactModify.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: ':id/details',
                            name: 'contactDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/contacts/ContactModify.vue').default,
                            beforeEnter: routerGaurd, 
                        }
                    ]
                },

                // Job Profiles
                {
                    path: '/profile',
                    name: 'profile',
                    meta: { requireAuth: true },
                    redirect: { name: 'profileList' },
                    component: require('./components/pages/dashboard/profiles/Profiles.vue').default,
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'profileList',
                            props: true,
                            meta: {
                                resourceName: 'job_profile',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'list']
                            },                            
                            component: require('./components/pages/dashboard/profiles/ProfileList.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'create',
                            name: 'profileCreate',
                            meta: {
                                resourceName: 'job_profile',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'create']
                            },
                            component: require('./components/pages/dashboard/profiles/ProfileModify.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: ':id/details',
                            name: 'profileDetails',
                            props: true,
                            meta: {
                                resourceName: 'job_profile',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'update']
                            },
                            component: require('./components/pages/dashboard/profiles/ProfileModify.vue').default,
                            beforeEnter: routerGaurd, 
                        }
                    ]
                },

                // Users
                {
                    path: '/user',
                    name: 'user',
                    meta: { requireAuth: true },
                    redirect: { name: 'userList' },
                    component: require('./components/pages/dashboard/users/Users.vue').default,
                    children: [
                        {
                            props: true,
                            path: 'list/:col?/:opt?/:val?',
                            name: 'userList',
                            meta: {
                                resourceName: 'user',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'list']
                            },
                            component: require('./components/pages/dashboard/users/UserList.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            props: true,
                            path: ':id/profile',
                            name: 'userProfile',
                            meta: { 
                                resourceName: 'user',
                                requireAuth: true,
                                requirePermission: false,
                                permissions: ['full', 'update']
                             },
                            component: require('./components/pages/dashboard/users/UserProfile.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                    ]
                },

                // Roles
                {
                    path: '/role',
                    name: 'role',
                    meta: { requireAuth: true },
                    redirect: { name: 'roleList' },
                    component: require('./components/pages/dashboard/roles/Roles.vue').default,
                    children: [
                        {
                            path: 'list/:col?/:opt?/:val?',
                            name: 'roleList',
                            props: true,
                            meta: {
                                resourceName: 'role',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'list']
                            },                            
                            component: require('./components/pages/dashboard/roles/RoleList.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'create',
                            name: 'roleCreate',
                            meta: {
                                resourceName: 'role',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'create']
                            },
                            component: require('./components/pages/dashboard/roles/RoleModify.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: ':id/details',
                            name: 'roleDetails',
                            props: true,
                            meta: {
                                resourceName: 'role',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'update']
                            },
                            component: require('./components/pages/dashboard/roles/RoleModify.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: ':id/users',
                            name: 'roleUsers',
                            props: true,
                            meta: {
                                resourceName: 'role',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'update']
                            },
                            component: require('./components/pages/dashboard/roles/RoleUsers.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                    ]
                },

                // Comments
                {
                    path: '/comments',
                    name: 'comments',
                    meta: { requireAuth: true },
                    redirect: { name: 'roleList' },
                    component: require('./components/pages/dashboard/comments/Comments.vue').default,
                    children: [
                        {
                            path: '/comments/:entity/:id',
                            name: 'commentList',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/comments/CommentList.vue').default,
                            beforeEnter: routerGaurd, 
                        }]
                },

                // Settings
                {
                    path: '/settings',
                    name: 'settings',
                    meta: { requireAuth: true },
                    redirect: { name: 'userSettings' },
                    component: require('./components/pages/dashboard/settings/Settings.vue').default,
                    children: [
                        {
                            path: 'user',
                            name: 'userSettings',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/settings/UserSettings.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'client',
                            name: 'clientSettings',
                            meta: {
                                resourceName: 'client',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'update']
                            },
                            component: require('./components/pages/dashboard/settings/ClientSettings.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                    ]
                },

                // wizards
                {
                    path: '/wizard',
                    name: 'wizard',
                    meta: { requireAuth: true },
                    redirect: { name: 'userWizard' },
                    component: require('./components/pages/wizards/Wizards.vue').default,
                    children: [
                        {
                            path: 'client/:startstep?',
                            props: true,
                            name: 'clientWizard',
                            meta: {
                                resourceName: 'client',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'update']
                            },
                            component: require('./components/pages/wizards/ClientWizard.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'user',
                            name: 'userWizard',
                            meta: { requireAuth: true },
                            component: require('./components/pages/wizards/UserWizard.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                        {
                            path: 'quickbooks',
                            name: 'quickbooskWizard',
                            meta: {
                                resourceName: 'client',
                                requireAuth: true,
                                requirePermission: true,
                                permissions: ['full', 'update']
                            },
                            component: require('./components/pages/wizards/QuickBooks.vue').default,
                            beforeEnter: routerGaurd, 
                        },
                    ]
                },                
            ]
        },

        // LoggedOut
        {
            path: '/loggedout',
            name: 'loggedout',
            meta: { requireAuth: false },
            component: require('./components/pages/LoggedOut.vue').default,
        },

        // 403
        {
            path: '/forbidden',
            name: 'forbidden',
            meta: { requireAuth: false },
            component: require('./components/pages/Forbidden.vue').default,
        },

        // 404
        {
            path: '/*',
            name: 'NotFound',
            meta: { requireAuth: false },
            component: require('./components/pages/NotFound.vue').default,
        },
    ]
});


function routerGaurd(to, from, next) {
    let user = store.state.user;
    let userCan = store.getters['user/can'];
    let authUrl = store.getters.getAuthUrl;

    // Check Authentication
    if (to.meta.requireAuth && !user.isAuth) {
        window.location.href = authUrl;
    }

    // Check Permissions
    if (to.meta.resourceName && to.meta.permissions)
        if (!userCan(to.meta.resourceName, to.meta.permissions) && to.meta.requirePermission) {
            next({ name: 'forbidden' });
        }

    next();
}
