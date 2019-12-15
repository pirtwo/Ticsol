<template>
  <div
    id="drawer"
    class="drawer"
  >
    <!-- drawer -->
    <div
      id="drawer__drawer"
      class="drawer__drawer"
      :class="[showDrawer? 'drawer__drawer--open' : 'drawer__drawer--close']"
    >
      <div
        v-if="drawerToolbar"
        class="drawer__drawer__toolbar"
      >
        <div
          class="drawer__drawer__title text-center"
          v-if="drawerTitle !== '' "
        >
          {{ drawerTitle }}
        </div>
        <slot name="drawer-toolbar" />
      </div>
      <div class="drawer__drawer__content">
        <slot name="drawer" />
      </div>
    </div>

    <!-- content -->
    <div
      id="drawer__body"
      class="drawer__body"
    >
      <!-- toolbar -->
      <div
        id="drawer__toolbar"
        class="drawer__toolbar"
      >        
        <div class="drawer__toolbar__controls d-flex flex-grow-1">
          <!-- Mini Dashboard -->
          <div class="mini-dashboard mr-1 btn-group dropdown d-lg-none">
            <button
              type="button"
              class="btn btn-sm ml-auto"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              data-offset="-100,0"
            >
              <i class="icon material-icons">apps</i>
            </button>
            <div class="dropdown-menu">
              <div class="row">
                <div class="col-sm-12">
                  <h6 class="dropdown-header">
                    Dashboard
                  </h6>
                  <div class="dropdown-divider" />

                  <!-- Home -->
                  <router-link
                    :to="{ name : 'dash' }"
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">dashboard</i>
                    <span class="caption">Home</span>
                  </router-link>

                  <!-- Inbox -->
                  <router-link
                    :to="{ name : 'inbox' }"
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">inbox</i>
                    <span class="caption">Inbox</span>
                  </router-link>

                  <!-- Leave -->
                  <router-link
                    :to="{ name : 'leaveCreate'}"
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">message</i>
                    <span class="caption">Leave</span>
                  </router-link>

                  <!-- Reimbursement -->
                  <router-link
                    :to="{ name : 'reimbCreate'}"
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">attach_money</i>
                    <span class="caption">Reimb</span>
                  </router-link>

                  <!-- Timesheets -->
                  <router-link
                    to="/timesheet"
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">timer</i>
                    <span class="caption">Timesheet</span>
                  </router-link>

                  <!-- Scheduler -->
                  <router-link
                    :to="{ name : 'scheduler' }"
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">calendar_today</i>
                    <span class="caption">Schedule</span>
                  </router-link>

                  <!-- Job -->
                  <router-link
                    :to="{ name : 'jobList'}"
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">work</i>
                    <span class="caption">Jobs</span>
                  </router-link>
                
                  <!-- Job Profile -->
                  <router-link
                    :to="{ name : 'profileList'}"
                    v-if="userCan('job_profile', ['full', 'list', 'create', 'update'])"                      
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">assignment</i>
                    <span class="caption">Profiles</span>
                  </router-link>
                  
                  <!-- Users -->
                  <router-link
                    :to="{ name : 'userList'}"    
                    v-if="userCan('user', ['full', 'list', 'create', 'update'])"                                     
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">person</i>
                    <span class="caption">Users</span>
                  </router-link>

                  <!-- Roles -->
                  <router-link
                    :to="{ name : 'roleList'}" 
                    v-if="userCan('role', ['full', 'list', 'create', 'update'])"                                                       
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">verified_user</i>
                    <span class="caption">Roles</span>
                  </router-link>
                  
                  <!-- Team -->
                  <router-link
                    :to="{ name : 'teamList'}"                                                    
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">group</i>
                    <span class="caption">Teams</span>
                  </router-link>
                  
                  <!-- Contacts -->
                  <router-link
                    :to="{ name : 'contactList'}"                                                   
                    class="btn ts-btn"
                    role="button"
                  >
                    <i class="icon material-icons">contact_mail</i>
                    <span class="caption">Contacts</span>
                  </router-link>
                  
                  <!-- Activities -->
                  <router-link
                    :to="{ name : 'activityList'}"
                    class="btn ts-btn"
                  >
                    <i class="icon material-icons">description</i>
                    <span class="caption">Reports</span>
                  </router-link>
                </div>                 
                <div class="col-sm-12">
                  <h6 class="dropdown-header">
                    Profile and Settings
                  </h6>
                  <div class="dropdown-divider" />

                  <!-- User Profile -->
                  <router-link
                    :to="{ name : 'userProfile', params: {id: userId} }"
                    class="btn ts-btn"
                  >
                    <i class="icon material-icons">account_circle</i>
                    <span class="caption">{{ userName }}</span>
                  </router-link>

                  <!-- User Settings -->
                  <router-link
                    :to="{ name : 'userSettings' }"
                    class="btn ts-btn"
                  >
                    <i class="icon material-icons">settings</i>
                    <span class="caption">Settings</span>            
                  </router-link>

                  <!-- Company Settings -->
                  <router-link
                    :to="{ name : 'clientSettings' }"
                    v-if="userCan('client', ['full', 'update'])"                      
                    class="btn ts-btn"
                  >
                    <i class="icon material-icons">domain</i>
                    <span class="caption">Company</span>   
                  </router-link>

                  <!-- Change Password -->
                  <a
                    class="btn ts-btn"
                    href="/password/change"
                  >
                    <i class="icon material-icons">lock</i>
                    <span class="caption">Change Password</span>
                  </a>

                  <!-- Logout -->
                  <a
                    class="btn ts-btn"
                    href="#"
                    @click.prevent="logoutHandler"
                  >
                    <i class="icon material-icons">exit_to_app</i>
                    <span class="caption">Logout</span>
                  </a>
                </div>                  
              </div>
            </div>
          </div>

          <!-- Drawer toggle button -->
          <button
            :class="[{ active: showDrawer }, 'btn btn-sm mr-1']"
            type="button"
            @click="onDrawer"
          >
            <ts-icon icon="menu" />
          </button>

          <!-- Back button -->
          <button
            class="btn btn-sm mr-1"
            type="button"
            @click="clickBack"
          >
            <ts-icon icon="arrow_back" />
          </button>

          <!-- Forward button -->
          <button
            class="btn btn-sm mr-1"
            type="button"
            @click="clickForward"
          >
            <ts-icon icon="arrow_forward" />
          </button>

          <!-- Fullscreen button -->
          <button
            :class="[{ active: fullscreen }, 'btn btn-sm mr-1']"
            type="button"
            @click="onFullscreen"
          >
            <ts-icon :icon="fullscreen ? 'fullscreen_exit' : 'fullscreen'" />
          </button>

          <!-- title -->
          <span class="">{{ menuTitle }}</span>
        </div>

        <!-- toolbar slot -->
        <button
          type="button"
          class="btn btn-sm d-xl-none"
          @click="toggleTools"
        >
          <ts-icon icon="view_column" />
        </button>
        <div class="drawer__toolbar__tools d-none d-xl-flex">
          <slot name="toolbar" />
        </div>        
      </div>

      <ts-snackbar
        v-model="snackbar.show"
        :class="['snackbar', snackbar.theme]"
      >
        <span v-html="snackbar.message" />
      </ts-snackbar>

      <!-- content -->
      <div
        id="drawer__content"
        class="drawer__content"
        :class="[{ 'scrollbar-show' : scrollbar}, padding]"
      >
        <slot name="content" />
      </div>
    </div>

    <!-- LoadingScreen -->
    <loading-screen :show="loading" />
  </div>
</template>

<script>
import _ from "lodash";
import { mapActions, mapGetters } from "vuex";
import AppLoadingScreen from "./AppLoadingScreen";
export default {
  name: "AppMain",

  props: {
    scrollbar: {
      type: Boolean,
      default: true
    },
    drawerToolbar: {
      type: Boolean,
      default: false
    },
    drawerTitle: {
      type: String,
      default: ""
    },
    menuTitle: {
      type: String,
      default: ""
    },
    padding: {
      type: String,
      default: ""
    },
    loading: {
      type: Boolean,
      default: false
    }
  },

  components: {
    "loading-screen": AppLoadingScreen
  },

  computed: {
    ...mapGetters({
      snackbar: "core/getSnackbar",
      fullscreen: "core/getUiFullscreen",
      showDrawer: "core/getDrawerStatus",
      userCan: "user/can",
      userId: "user/getId",
      avatar: "user/getAvatar",
      userName: "user/getUsername"
    }),

    enablePadding: function() {
      return this.padding !== "";
    }
  },

  methods: {
    ...mapActions({
      logout: "user/logout",
      toggleDrawer: "core/drawer",
      toggleFullscreen: "core/fullscreen"
    }),

    onDrawer(e) {
      this.toggleDrawer({ show: !this.showDrawer });
      e.preventDefault();
    },

    toggleTools(e) {
      if ($(".drawer__toolbar__tools").children().length > 0) {
        $(".drawer__toolbar__tools").toggleClass("d-none");
      }
      e.preventDefault();
    },

    onFullscreen(e) {
      var element = document.documentElement;
      if (!this.fullscreen) {
        if (element.requestFullScreen) {
          element.requestFullScreen();
          this.toggleFullscreen({ enable: true });
        } else if (element.webkitRequestFullScreen) {
          element.webkitRequestFullScreen();
          this.toggleFullscreen({ enable: true });
        } else if (element.mozRequestFullScreen) {
          element.mozRequestFullScreen();
          this.toggleFullscreen({ enable: true });
        } else if (element.msRequestFullscreen) {
          element.msRequestFullscreen();
          this.toggleFullscreen({ enable: true });
        }
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
          this.toggleFullscreen({ enable: false });
        } else if (document.webkitExitFullscreen) {
          document.webkitExitFullscreen();
          this.toggleFullscreen({ enable: false });
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
          this.toggleFullscreen({ enable: false });
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
          this.toggleFullscreen({ enable: false });
        }
      }
    },
    
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
    },

    clickForward(e) {
      this.$router.go(1);
    },

    clickBack(e) {
      this.$router.go(-1);
    }
  }
};
</script>

<style lang="scss" scoped>
.drawer {
  height: 100%;
  display: flex;
  position: relative;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.drawer__drawer {
  width: 230px;
  height: 100%;
  overflow: hidden;
  transition: all 0.5s cubic-bezier(0.075, 0.82, 0.165, 1);
}

@media (min-width: 0px) {
  .drawer__drawer {
    top: 60px;
    left: -250px;
    z-index: 10;
    position: absolute;
    -webkit-box-shadow: 6px 0px 5px -5px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: 6px 0px 5px -5px rgba(0, 0, 0, 0.75);
    box-shadow: 6px 0px 5px -5px rgba(0, 0, 0, 0.75);
  }

  .drawer__drawer__toolbar{
    height: 50px;
  }

  .drawer__drawer__content{
    height: calc(100% - 10% - 60px);
  }

  .drawer__drawer--open {
    left: 0px;
  }

  .drawer__drawer--close {
    left: -250px;
  }
}

@media (min-width: 992px) {
  .drawer__drawer {
    top: 0px;
    z-index: 0;
    display: flex;
    flex-flow: column;
    margin-right: 8px;  
    box-shadow: none;
    position: relative;
  }

  .drawer__drawer--open {
    width: 270px;
  }

  .drawer__drawer--close {
    width: 0px;
    overflow: hidden;
    margin-right: 0px;
  }
}

.drawer__body {
  width: 1px;
  height: 100%;
  flex-grow: 1;
  transition: all 0.5s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.drawer__toolbar {
  display: flex;
  position: relative;  
  margin-bottom: 5px;

  > .btn,
  .drawer__toolbar__tools .btn {
    display: flex;
    align-items: center;
  }

  .btn,
  .btn i {
      line-height: inherit;
  }
}

.drawer__content {
  background-color: #03a9f4;
  overflow-x: hidden;
  overflow-y: auto;
}

@media (min-width: 0px){
  .drawer__toolbar {
    height: 55px;    
    padding: 5px 7px;
  }

  // 100% - (toolbarHight + marginBot)
  .drawer__content {
    height: calc(100% - 60px);
  }  
}

@media (min-width: 992px){
  .drawer__toolbar {
    height: 45px;
  }

  // 100% - (toolbarHight + marginBot)
  .drawer__content {
    height: calc(100% - 50px);
  }
}

.drawer__drawer__toolbar {
  flex: 0 1 auto;
  margin-bottom: 5px;
}

.drawer__drawer__content {
  flex: 1 1 auto;
  overflow-y: auto;
  overflow-x: hidden;
}

.drawer__drawer__title {
  width: 100%;
  padding: 5px;
  color: rgba(0, 0, 0, 0.3);
}

.drawer__toolbar__tools {
  display: flex;
  transition: all 0.5s cubic-bezier(0.075, 0.82, 0.165, 1);
}

@media (min-width: 0px) {
  .drawer__toolbar__tools {
    right: 5px;
    bottom: -100%;
    z-index: 20;
    height: 50px;
    padding: 5px;
    position: absolute;
    background-color: white;
    -webkit-box-shadow: -10px 10px 5px -8px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: -10px 10px 5px -8px rgba(0, 0, 0, 0.75);
    box-shadow: -10px 10px 5px -8px rgba(0, 0, 0, 0.75);
  }
}

@media (min-width: 1200px){
  .drawer__toolbar__tools {
    display: flex;
    height: auto;
    right: 0px;
    bottom: 0px;
    padding: 0px;
    z-index: 0;
    box-shadow: none;    
    position: relative;
    background-color: transparent;
  }
}

.snackbar {
  position: absolute;
  left: 0px;
  top: 55px;
  width: 100%;
  height: 50px;
}

.scrollbar-show {
  overflow-y: scroll !important;
}
</style>