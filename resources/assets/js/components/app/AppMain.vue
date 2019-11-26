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
      <nav
        id="drawer__toolbar"
        class="drawer__toolbar navbar navbar-light"
      >
        <div class="d-flex align-items-center w-100">
          <div class="drawer__toolbar__controls flex-grow-1">
            <!-- Drawer toggle button -->
            <button
              :class="[{ active: showDrawer }, 'btn btn-sm ml-auto']"
              type="button"
              @click="onDrawer"
            >
              <i class="material-icons">menu</i>
            </button>

            <!-- Back button -->
            <button
              class="btn btn-sm ml-auto"
              type="button"
              @click="clickBack"
            >
              <i class="material-icons">arrow_back</i>
            </button>

            <!-- Forward button -->
            <button
              class="btn btn-sm ml-auto"
              type="button"
              @click="clickForward"
            >
              <i class="material-icons">arrow_forward</i>
            </button>

            <!-- Fullscreen button -->
            <button
              :class="[{ active: fullscreen }, 'btn btn-sm ml-auto']"
              type="button"
              @click="onFullscreen"
            >
              <i class="material-icons">{{ fullscreen ? "fullscreen_exit" : "fullscreen" }}</i>
            </button>

            <!-- title -->
            <span class="navbar-brand">{{ menuTitle }}</span>
          </div>

          <!-- toolbar slot -->
          <div class="drawer__toolbar__tools">
            <slot name="toolbar" />
          </div>
        </div>
      </nav>

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

  data() {
    return {};
  },

  computed: {
    ...mapGetters({
      snackbar: "core/getSnackbar",
      fullscreen: "core/getUiFullscreen",
      showDrawer: "core/getDrawerStatus"
    }),

    enablePadding: function() {
      return this.padding !== "";
    }
  },

  methods: {
    ...mapActions({
      toggleDrawer: "core/drawer",
      toggleFullscreen: "core/fullscreen"
    }),

    onDrawer(e) {
      this.toggleDrawer({ show: !this.showDrawer });
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
  display: flex;
  flex-flow: column;
  width: 230px;
  height: 100%;
  overflow: hidden;
  margin-right: 8px;
  background-color: transparent;
  transition: all 0.5s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.drawer__body {
  width: 1px;
  height: 100%;
  flex-grow: 1;
  transition: all 0.5s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.drawer__toolbar {
  height: 10%;
  margin-bottom: 5px;
}

.drawer__content {
  height: calc(90% - 5px);
  background-color: #03a9f4;
  overflow-x: hidden;
  overflow-y: auto;
}

.drawer__drawer--open {
  width: 270px;
}

.drawer__drawer--close {
  width: 0px;
  overflow: hidden;
  margin-right: 0px;
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

.drawer__toolbar .btn,
.drawer__toolbar .btn i {
  font-size: 1.2rem;
  line-height: 1.2;
}

.drawer__toolbar__tools {
  display: flex;
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

i.material-icons {
  transition: all 0.5s cubic-bezier(0.075, 0.82, 0.165, 1);
}
</style>