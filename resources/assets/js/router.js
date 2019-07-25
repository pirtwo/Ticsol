import Vue from 'vue';
import VueRouter from 'vue-router';
import { store } from './store/store.js';

Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    routes: [
        // Auth
        {
            path: '/auth',
            name: 'auth',
            redirect: { name: 'signout' },
            component: require('./components/pages/auth/Auth.vue').default,
            children: [
                {
                    path: '/signout',
                    name: 'signout',
                    meta: { requireAuth: false },
                    component: require('./components/pages/auth/SignOut.vue').default
                }
            ]
        },

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
            children: [

                // Home
                {
                    path: '/home',
                    name: 'home',
                    meta: { requireAuth: true },
                    component: require('./components/pages/dashboard/Home.vue').default
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
                        },
                        {
                            name: 'leaveCreate',
                            meta: { requireAuth: true },
                            path: 'leave/create',
                            component: require('./components/pages/dashboard/requests/LeaveRequest.vue').default,
                        },
                        {
                            props: true,
                            name: 'leaveDetails',
                            meta: { requireAuth: true },
                            path: 'leave/:id/details',
                            component: require('./components/pages/dashboard/requests/LeaveRequest.vue').default,
                        },
                        {
                            name: 'reimbCreate',
                            meta: { requireAuth: true },
                            path: 'reimbursement',
                            component: require('./components/pages/dashboard/requests/ReimbRequest.vue').default,
                        },
                        {
                            props: true,
                            name: 'reimbDetails',
                            meta: { requireAuth: true },
                            path: 'reimbursement/:id/details',
                            component: require('./components/pages/dashboard/requests/ReimbRequest.vue').default,
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
                        },
                        {
                            path: 'create',
                            name: 'teamCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/team/TeamModify.vue').default,
                        },
                        {
                            path: ':id/details',
                            name: 'teamDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/team/TeamModify.vue').default,
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
                        },
                        {
                            path: ':id/:start/:end/details',
                            name: 'timesheetDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/timesheets/TimesheetModify.vue').default,
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
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobList.vue').default,
                        },
                        {
                            path: 'create',
                            name: 'jobCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobModify.vue').default,
                        },
                        {
                            path: ':id/details',
                            name: 'jobDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/jobs/JobModify.vue').default,
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
                        },
                        {
                            path: 'create',
                            name: 'activityCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/activities/ActivityModify.vue').default,
                        },
                        {
                            path: ':id/details',
                            name: 'activityDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/activities/ActivityModify.vue').default,
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
                        },
                        {
                            path: 'create',
                            name: 'contactCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/contacts/ContactModify.vue').default,
                        },
                        {
                            path: ':id/details',
                            name: 'contactDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/contacts/ContactModify.vue').default,
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
                            meta: { requireAuth: true },
                            props: true,
                            component: require('./components/pages/dashboard/profiles/ProfileList.vue').default,
                        },
                        {
                            path: 'create',
                            name: 'profileCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/profiles/ProfileModify.vue').default,
                        },
                        {
                            path: ':id/details',
                            name: 'profileDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/profiles/ProfileModify.vue').default,
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
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/users/UserList.vue').default,
                        },
                        {
                            props: true,
                            path: ':id/profile',
                            name: 'userProfile',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/users/UserProfile.vue').default,
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
                            meta: { requireAuth: true },
                            props: true,
                            component: require('./components/pages/dashboard/roles/RoleList.vue').default,
                        },
                        {
                            path: 'create',
                            name: 'roleCreate',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/roles/RoleModify.vue').default,
                        },
                        {
                            path: ':id/details',
                            name: 'roleDetails',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/roles/RoleModify.vue').default,
                        },
                        {
                            path: ':id/users',
                            name: 'roleUsers',
                            props: true,
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/roles/RoleUsers.vue').default,
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
                        }]
                },

                // Settings
                {
                    path: '/settings',
                    name: 'settings',
                    meta: { requireAuth: true },
                    redirect: { name: 'roleList' },
                    component: require('./components/pages/dashboard/Settings/Settings.vue').default,
                    children: [
                        {
                            path: '/settings',
                            name: 'settingModify',
                            meta: { requireAuth: true },
                            component: require('./components/pages/dashboard/Settings/SettingModify.vue').default,
                        }]
                },
            ]
        },
    ]
});

// check auth
router.beforeEach((to, from, next) => {

    let user = store.state.user;
    let authUrl = store.getters.getAuthUrl;

    // redirect to home if user authenticated.
    if (to.name === "index") {
        if (user.isAuth) next('/home');        
    }

    // if not authenticated 
    // redirect to login page
    if (to.meta.requireAuth && !user.isAuth) {
        window.location.href = authUrl;
    }

    next();
});