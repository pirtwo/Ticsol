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
              <ts-icon icon="menu" />
            </button>

            <!-- Back button -->
            <button
              class="btn btn-sm ml-auto"
              type="button"
              @click="clickBack"
            >
              <ts-icon icon="arrow_back" />
            </button>

            <!-- Forward button -->
            <button
              class="btn btn-sm ml-auto"
              type="button"
              @click="clickForward"
            >
              <ts-icon icon="arrow_forward" />
            </button>

            <!-- Fullscreen button -->
            <button
              :class="[{ active: fullscreen }, 'btn btn-sm ml-auto']"
              type="button"
              @click="onFullscreen"
            >
              <ts-icon :icon="fullscreen ? 'fullscreen_exit' : 'fullscreen'" />
            </button>

            <!-- title -->
            <span class="navbar-brand">{{ menuTitle }}</span>
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
    top: calc(10% + 5px);
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

@media (min-width: 768px) {
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
  height: 10%;
  margin-bottom: 5px;
}

.drawer__content {
  height: calc(90% - 5px);
  background-color: #03a9f4;
  overflow-x: hidden;
  overflow-y: auto;
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
    right: 17px;
    bottom: calc(-100% + 15px);
    z-index: 20;
    padding: 10px;
    position: absolute;
    background-color: whitesmoke;
    -webkit-box-shadow: -10px 10px 5px -8px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: -10px 10px 5px -8px rgba(0, 0, 0, 0.75);
    box-shadow: -10px 10px 5px -8px rgba(0, 0, 0, 0.75);
  }
}

@media (min-width: 1200px){
  .drawer__toolbar__tools {
    display: flex;
    right: 0px;
    padding: 0px;
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