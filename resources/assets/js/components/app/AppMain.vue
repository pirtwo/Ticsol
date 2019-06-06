<template>
  <div class="wrap-drawer">
    <!-- drawer -->
    <div
      class="drawer"
      :class="[showDrawer? 'open' : 'close']"
    >
      <div
        v-if="drawerToolbar"
        class="drawer-toolbar"
      >
        <div
          class="title text-center"
          v-if="drawerTitle !== '' "
        >
          {{ drawerTitle }}
        </div>
        <slot name="drawer-toolbar" />
      </div>
      <div class="drawer-body">
        <slot name="drawer" />
      </div>
    </div>

    <!-- content -->
    <div class="wrap-content">
      <!-- loadingbox -->
      <div
        class="wrap-loading"
        v-show="loading"
      >
        <div class="loading-box shadow-sm">
          <div
            class="spinner-border"
            role="status"
          >
            <span class="sr-only">Loading...</span>
          </div>
          <div class="caption">
            Loading, Please wait...
          </div>
        </div>
      </div>

      <!-- toolbar -->
      <nav class="navbar navbar-light">
        <div class="d-flex w-100">
          <div class="flex-grow-1">
            <button
              class="btn btn-light btn-sm ml-auto"
              type="button"
              @click="onDrawer"
            >
              <i class="material-icons">{{ showDrawer ? "close" : "menu" }}</i>
            </button>
            <button
              class="btn btn-light btn-sm ml-auto"
              type="button"
              @click="clickBack"
            >
              <i class="material-icons">arrow_back</i>
            </button>
            <button
              class="btn btn-light btn-sm ml-auto"
              type="button"
              @click="clickForward"
            >
              <i class="material-icons">arrow_forward</i>
            </button>
            <button
              class="btn btn-light btn-sm ml-auto"
              type="button"
              @click="onFullscreen"
            >
              <i class="material-icons">{{ fullscreen ? "fullscreen_exit" : "fullscreen" }}</i>
            </button>

            <span class="navbar-brand">{{ menuTitle }}</span>
          </div>
          <div class="toolbar">
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

      <div
        class="content"
        :class="[{ 'scrollbar-show' : scrollbar}, padding]"
      >
        <slot name="content" />
      </div>
    </div>
  </div>
</template>

<script>
import _ from "lodash";
import { mapActions, mapGetters } from "vuex";
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

  data() {
    return {};
  },

  watch: {
    contentHeight: function(value) {
      $(".content").outerHeight(value);
    }
  },

  computed: {
    ...mapGetters({
      snackbar: "core/getSnackbar",
      fullscreen: "core/getUiFullscreen",
      showDrawer: "core/getDrawerStatus",
      contentHeight: "core/getUiContentHeight"
    }),

    enablePadding: function() {
      return this.padding !== "";
    }
  },

  mounted() {
    this.$nextTick(this.setContentHeight);
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
    },

    setContentHeight() {
      $(".content").outerHeight(this.contentHeight);
    }
  }
};
</script>

<style lang="scss" scoped>
.wrap-drawer {
  height: 100%;
  display: flex;
  position: relative;
  transition: all 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.wrap-content {
  width: 100%;
  max-height: 100%;
  position: relative;
  overflow-y: hidden;
}

.content {
  position: relative;
  //background-color: rgba(255, 255, 255, 0.8);
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

.drawer {
  display: flex;
  flex-flow: column;
  width: 230px;
  height: 100%;
  overflow: hidden;
  margin-right: 8px;
  background-color: transparent;
  transition: all 0.5s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.drawer.open {
  width: 270px;
}

.drawer.close {
  width: 0px;
  overflow: hidden;
  margin-right: 0px;
}

.drawer-toolbar {
  flex: 0 1 auto;
  margin-bottom: 5px;
}

.drawer-body {
  flex: 1 1 auto;
  overflow-y: auto;
  //background-color: rgba(255, 255, 255, 0.95);
}

.drawer .title {
  width: 100%;
  padding: 5px;
  color: rgba(0, 0, 0, 0.3);
}

.navbar {
  height: 50px;
  margin-bottom: 5px;
  display: -webkit-box !important;
  //background-color: rgba(255, 255, 255, 0.8);
}

.navbar .btn,
.navbar .btn i {
  font-size: 1.2rem;
  line-height: 1.2;
}

i.material-icons {
  transition: all 0.5s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.navbar-brand {
  font-size: 1.2rem;
  line-height: 1.2;
}

.toolbar {
  display: flex;
}

.wrap-loading {
  margin-top: 55px;
  width: 100%;
  height: 100%;
  position: absolute;
  background-color: rgba(34, 34, 34, 0.1);
  z-index: 100;
}

.loading-box {
  left: 50%;
  top: 45%;
  width: 17vw;
  height: auto;
  padding: 1vw;
  position: relative;
  border-radius: 0px;
  transform: translate(-50%, -80%);
  //background-color: rgba(44, 44, 44, 0.9);
}

.loading-box .spinner-border {
  width: 2vw;
  height: 2vw;
  border: 0.4vw solid currentColor;
  border-right-color: transparent;
}

.wrap-loading .caption {
  margin-left: 10px;
  display: inline;
  text-align: center;
  vertical-align: super;
}
</style>