<template>
  <div class="toolbar-top">
    <div class="d-flex align-items-stretch ml-auto">
      <notifications-log class="mr-1" />

      <!-- Settings -->
      <div class="btn-group dropdown">
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
            :to="{ name : 'clientSettings' }"
            class="dropdown-item"
          >
            <i class="icon material-icons">domain</i>Golobal
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
          this.$router.push({ name: "signout" });
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
.user-avatar {
  width: 40px;
  height: 40px;
}

.user-name {
  width: 90px;
  line-height: 1;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 0.7rem !important;
  display: inline-block !important;
}

.dropdown {
  display: inline-block !important;
}

.dropdown-item i {
  color: inherit !important;
  margin-right: 5px;
  vertical-align: top;
}
</style>