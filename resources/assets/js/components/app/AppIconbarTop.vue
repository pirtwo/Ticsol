<template>
  <div class="toolbar-top d-none d-lg-block">
    <div class="d-flex align-items-stretch w-100">
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
</style>