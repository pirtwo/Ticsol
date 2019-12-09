<template>
  <div class="toolbar-top">
    <div class="d-flex align-items-stretch w-100">
      <!-- menu for xs devices -->
      <div class="small-device-menue mr-auto btn-group dropdown d-block d-md-none">
        <button
          type="button"
          class="btn"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="icon material-icons">apps</i>
          <span class="caption">Menu</span>
        </button>
        <div class="dropdown-menu">
          <div class="row">
            <div class="col-sm-6">
              <h6 class="dropdown-header">Dashboard</h6>
              <div class="dropdown-divider" />

              <!-- Home -->
              <router-link
                :to="{ name : 'dash' }"
                class="btn"
                role="button"
              >
                <i class="icon material-icons">dashboard</i>
                <span class="caption">HOME</span>
              </router-link>

              <!-- Inbox -->
              <router-link
                :to="{ name : 'inbox' }"
                class="btn"
                role="button"
              >
                <i class="icon material-icons">inbox</i>
                <span class="caption">INBOX</span>
              </router-link>

              <!-- Leave -->
              <router-link
                :to="{ name : 'leaveCreate'}"
                class="btn"
                role="button"
              >
                <i class="icon material-icons">message</i>
                <span class="caption">Leave</span>
              </router-link>

              <!-- Reimbursement -->
              <router-link
                :to="{ name : 'reimbCreate'}"
                class="btn"
                role="button"
              >
                <i class="icon material-icons">message</i>
                <span class="caption">Reimb</span>
              </router-link>

              <!-- Timesheets -->
              <router-link
                to="/timesheet"
                class="btn"
                role="button"
              >
                <i class="icon material-icons">timer</i>
                <span class="caption">TIMESHEETS</span>
              </router-link>

              <!-- Scheduler -->
              <router-link
                :to="{ name : 'scheduler' }"
                class="btn"
                role="button"
              >
                <i class="icon material-icons">calendar_today</i>
                <span class="caption">SCHEDULE</span>
              </router-link>
            </div>
            <div class="col-sm-6">
              <h6 class="dropdown-header">More</h6>
              <div class="dropdown-divider" />
              <!-- Job -->
              <router-link
                :to="{ name : 'jobList'}"
                class="dropdown-item"
              >
                <i class="icon material-icons">work</i>Jobs
              </router-link>

              <!-- Job Profile -->
              <router-link
                v-if="userCan('job_profile', ['full', 'list', 'create', 'update'])"
                :to="{ name : 'profileList'}"
                class="dropdown-item"
              >
                <i class="icon material-icons">assignment</i>Job Profiles
              </router-link>

              <!-- Users -->
              <router-link
                v-if="userCan('user', ['full', 'list', 'create', 'update'])"
                :to="{ name : 'userList'}"
                class="dropdown-item"
              >
                <i class="icon material-icons">person</i>Users
              </router-link>

              <!-- Roles -->
              <router-link
                v-if="userCan('role', ['full', 'list', 'create', 'update'])"
                :to="{ name : 'roleList'}"
                class="dropdown-item"
              >
                <i class="icon material-icons">verified_user</i>Roles
              </router-link>

              <!-- Team -->
              <router-link
                :to="{ name : 'teamList'}"
                class="dropdown-item"
              >
                <i class="icon material-icons">group</i>Teams
              </router-link>

              <!-- Contacts -->
              <router-link
                :to="{ name : 'contactList'}"
                class="dropdown-item"
              >
                <i class="icon material-icons">contact_mail</i>Contacts
              </router-link>

              <!-- Activities -->
              <router-link
                :to="{ name : 'activityList'}"
                class="dropdown-item"
              >
                <i class="icon material-icons">description</i>Activity Reports
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <notifications-log class="ml-auto" />

      <!-- Settings -->
      <div class="btn-group dropdown ml-1">
        <button 
          type="button" 
          class="btn" 
          data-toggle="dropdown" 
          aria-haspopup="true" 
          aria-expanded="false"
        >
          <i class="icon material-icons">
            settings
          </i>
          <span class="caption">SETTINGS</span>
        </button>
        <div class="dropdown-menu">
          <router-link
            :to="{ name : 'userSettings' }"
            class="dropdown-item"
          >
            <i class="icon material-icons">person</i>User            
          </router-link>

          <router-link
            v-if="userCan('client', ['full', 'update'])"
            :to="{ name : 'clientSettings' }"
            class="dropdown-item"
          >
            <i class="icon material-icons">domain</i>Global
          </router-link>
        </div>
      </div>

      <!-- Profile -->
      <div class="dropdown ml-1">
        <button
          class="btn h-100"
          type="buttom"
          role="button"
          id="dropdownMenuLink"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <img
            class="user-avatar rounded"
            :src="avatar"
            alt="Avtar"
          >
          <span class="caption user-name">{{ userName }}</span>
        </button>
        <div
          class="dropdown-menu dropdown-menu-right"
          aria-labelledby="dropdownMenuLink"
        >
          <router-link
            :to="{ name : 'userProfile', params: {id: userId} }"
            class="dropdown-item"
          >
            <i class="icon material-icons">account_circle</i>Profile
          </router-link>
          <a
            class="dropdown-item"
            href="/password/change"
          >
            <i class="icon material-icons">lock</i>Change Password
          </a>
          <div class="dropdown-divider" />
          <a
            class="dropdown-item"
            href="#"
            @click.prevent="logoutHandler"
          >
            <i class="icon material-icons">exit_to_app</i>Log out
          </a>
        </div>
      </div>
    </div>
    <notifications />    
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
import Notifs from "./AppNotifications";
import NotifsLog from "../app/AppNotificationsLog";

export default {
  name: "AppIconbarTop",

  components: {
    notifications: Notifs,
    "notifications-log": NotifsLog
  },

  computed: {
    ...mapGetters({
      userCan: "user/can",
      userId: "user/getId",
      avatar: "user/getAvatar",
      userName: "user/getUsername"
    })
  },

  methods: {
    ...mapActions({ logout: "user/logout" }),
    logoutHandler() {
      this.logout()
        .then(() => {
          console.log("successful logout");
          this.$router.push({ name: "loggedout" });
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
        });
    }
  }
};
</script>

<style lang="scss" scoped>
.user-avatar {
  width: 40px;
  height: 40px;
}

.user-name {
  width: 90px;
  line-height: 1;
  overflow: hidden;
  text-overflow: ellipsis;
  display: inline-block;
}

.dropdown {
  display: inline-block;
}

.dropdown-item i {
  color: inherit !important;
  margin-right: 5px;
  vertical-align: top;
}

.small-device-menue{
  .dropdown-menu{
    min-width: 400px;

    .btn{
      margin: 5px;
      height: auto !important;
    }
  }
}
</style>