<template>  
    <div class="wrap-drawer">      

        <!-- drawer -->
        <div class="drawer" :class="[menuVisible? 'open' : 'close']">
            <div class="drawer-toolbar">
              <div class="title text-center" v-if="drawerTitle !== '' ">{{ drawerTitle }}</div> 
              <slot name="drawer-toolbar"></slot>
            </div>             
            <slot name="drawer"></slot>
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
                <span class="caption">Loading, Please wait...</span>
              </div>
            </div>

            <!-- toolbar -->
            <nav class="navbar navbar-light bg-light">                
              <button class="btn btn-light btn-sm ml-auto" type="button" @click="toggleMenu">
                <i class="material-icons">{{ menuVisible ? "close" : "menu" }}</i>
              </button>
              <button class="btn btn-light btn-sm ml-auto" type="button" @click="clickBack">
                <i class="material-icons">arrow_back</i>
              </button>
              <button class="btn btn-light btn-sm ml-auto" type="button" @click="clickForward">
                <i class="material-icons">arrow_forward</i>
              </button>
              <button class="btn btn-light btn-sm ml-auto" type="button" @click="toggleFullscreen">
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
    return {
      menuVisible: true,
      fullscreen: false
    };
  },

  computed: {
    ...mapGetters({
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
    toggleMenu(e) {
      this.menuVisible = !this.menuVisible;
      e.preventDefault();
    },

    toggleFullscreen(e) {
      var element = document.documentElement;
      if (!this.fullscreen) {
        if (element.requestFullScreen) {
          element.requestFullScreen();
          this.fullscreen = true;
        } else if (element.webkitRequestFullScreen) {
          element.webkitRequestFullScreen();
          this.fullscreen = true;
        } else if (element.mozRequestFullScreen) {
          element.mozRequestFullScreen();
          this.fullscreen = true;
        } else if (element.msRequestFullscreen) {
          element.msRequestFullscreen();
          this.fullscreen = true;
        }
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
          this.fullscreen = false;
        } else if (document.webkitExitFullscreen) {
          document.webkitExitFullscreen();
          this.fullscreen = false;
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
          this.fullscreen = false;
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
          this.fullscreen = false;
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

<style scoped>
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
  background-color: rgba(255, 255, 255, 0.8);
}

.content {
  position: relative;
}

.scrollbar-show {
  overflow-y: scroll !important;
}

.drawer {
  width: 230px;
  min-height: 100%;
  overflow-y: auto;
  margin-right: 8px;
  transition: all 0.3s;
  background-color: rgba(255, 255, 255, 0.95);
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

.drawer .title{
  width: 100%;
  padding: 5px;  
  color: rgba(0, 0, 0, 0.3);  
}

.navbar {
  height: 70px;
  display: -webkit-box !important;
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
  top: 50%;
  left: 50%;
  position: absolute;
}

.loading-box {
  left: -50%;
  top: -50%;
  padding: 10px;
  color: white;
  position: relative;
  border-radius: 5px;
  background-color: rgba(72, 72, 72, 0.8);
}

.wrap-loading .md-progress-spinner {
  margin-left: 40%;
  margin-right: auto;
}

.wrap-loading .caption {
  display: flex;
}
</style>