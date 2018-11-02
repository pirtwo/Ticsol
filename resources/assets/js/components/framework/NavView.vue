<template>  
    <div class="wrap-drawer">      

        <!-- drawer -->
        <div class="drawer" :class="[showDrawer? 'open' : 'close']">
            <div class="drawer-toolbar">
              <div class="title text-center" v-if="drawerTitle !== '' ">{{ drawerTitle }}</div> 
              <slot name="drawer-toolbar"></slot>
            </div>     
            <div class="drawer-body">
              <slot name="drawer"></slot>
            </div> 
        </div>

        <!-- content -->
        <div class="wrap-content">

            <!-- loadingbox -->
            <div class="wrap-loading" v-show="loading">
              <div class="loading-box shadow-sm"> 
                <div class="sk-cube-grid">
                  <div class="sk-cube sk-cube1"></div>
                  <div class="sk-cube sk-cube2"></div>
                  <div class="sk-cube sk-cube3"></div>
                  <div class="sk-cube sk-cube4"></div>
                  <div class="sk-cube sk-cube5"></div>
                  <div class="sk-cube sk-cube6"></div>
                  <div class="sk-cube sk-cube7"></div>
                  <div class="sk-cube sk-cube8"></div>
                  <div class="sk-cube sk-cube9"></div>
                </div>               
                <div class="caption">Loading, Please wait...</div>
              </div>
            </div>

            <!-- toolbar -->
            <nav class="navbar navbar-light">                
              <button class="btn btn-light btn-sm ml-auto" type="button" @click="onDrawer">
                <i class="material-icons">{{ showDrawer ? "close" : "menu" }}</i>
              </button>
              <button class="btn btn-light btn-sm ml-auto" type="button" @click="clickBack">
                <i class="material-icons">arrow_back</i>
              </button>
              <button class="btn btn-light btn-sm ml-auto" type="button" @click="clickForward">
                <i class="material-icons">arrow_forward</i>
              </button>
              <button class="btn btn-light btn-sm ml-auto" type="button" @click="onFullscreen">
                <i class="material-icons">{{ fullscreen ? "fullscreen_exit" : "fullscreen" }}</i>
              </button>

              <span class="navbar-brand">{{ menuTitle }}</span>
              <slot name="toolbar"></slot>
            </nav>

            <div class="content" 
              :class="[{ 'scrollbar-show' : scrollbar}, padding]"              
              v-show="!loading">
              <slot name="content"></slot>
            </div>            
        </div>

    </div>
</template>

<script>
import _ from "lodash";
import { mapActions, mapGetters } from "vuex";
export default {
  name: "NavView",

  props: {
    scrollbar: {
      type: Boolean,
      default: true
    },
    menuTitle: {
      type: String,
      default: ""
    },
    drawerTitle: {
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

  computed: {
    ...mapGetters({
      fullscreen: "core/getUiFullscreen",
      showDrawer: "core/getDrawerStatus",
      contentHeight: "core/getUiContentHeight"
    }),

    enablePadding: function() {
      return this.padding !== "";
    }
  },

  watch: {
    contentHeight: function(value) {
      $(".content").outerHeight(value);
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
  background-color: rgba(255, 255, 255, 0.8);
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
  margin-bottom: 0px;
  background-color: rgba(255, 255, 255, 0.95);
}

.drawer-body {
  flex: 1 1 auto;
  overflow-y: auto;
  background-color: rgba(255, 255, 255, 0.95);
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
  background-color: rgba(255, 255, 255, 0.8);
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

.wrap-loading {
  margin-top: 55px;
  width: 100%;
  height: 100%;
  position: absolute;
  background-color: rgba(255, 255, 255, 0.8);
}

.loading-box {
  left: 50%;
  top: 50%;
  width: 200px;
  height: auto;
  padding: 10px;
  color: white;
  position: relative;
  border-radius: 5px;
  transform: translate(-50%, -80%);
  background-color: rgba(72, 72, 72, 0.8);
}

.wrap-loading .md-progress-spinner {
  margin-left: 40%;
  margin-right: auto;
}

.wrap-loading .caption {
  width: 100%;
  display: block;
  text-align: center;
}
</style>